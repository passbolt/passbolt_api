<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

/**
 * Swagger\StaticAnalyser extracts swagger-php annotations from php code using static analysis.
 */
class StaticAnalyser
{
    /**
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        if ($filename !== null) {
            $this->fromFile($filename);
        }
    }

    /**
     * Extract and process all doc-comments from a file.
     *
     * @param string $filename Path to a php file.
     * @return Analysis
     */
    public function fromFile($filename)
    {
        $tokens = token_get_all(file_get_contents($filename));
        return $this->fromTokens($tokens, new Context(['filename' => $filename]));
    }

    /**
     * Extract and process all doc-comments from the contents.
     *
     * @param string $code PHP code. (including <?php tags)
     * @param Context $context The original location of the contents.
     * @return Analysis
     */
    public function fromCode($code, $context)
    {
        $tokens = token_get_all($code);
        return $this->fromTokens($tokens, $context);
    }

    /**
     * Shared implementation for parseFile() & parseContents().
     *
     * @param array $tokens The result of a token_get_all()
     * @param Context $parseContext
     * @return Analysis
     */
    protected function fromTokens($tokens, $parseContext)
    {
        $analyser = new Analyser();
        $analysis = new Analysis();
        reset($tokens);
        $token = '';
        $imports = ['swg' => 'Swagger\Annotations']; // Use @SWG\* for swagger annotations (unless overwritten by a use statement)

        $parseContext->uses = [];
        $definitionContext = $parseContext; // Use the parseContext until a definitionContext  (class or trait) is created.
        $classDefinition = false;
        $comment = false;
        $line = 0;
        $lineOffset = $parseContext->line ? : 0;
        while ($token !== false) {
            $previousToken = $token;
            $token = $this->nextToken($tokens, $parseContext);
            if (is_array($token) === false) { // Ignore tokens like "{", "}", etc
                continue;
            }
            if ($token[0] === T_DOC_COMMENT) {
                if ($comment) { // 2 Doc-comments in succession?
                    $this->analyseComment($analysis, $analyser, $comment, new Context(['line' => $line], $definitionContext));
                }
                $comment = $token[1];
                $line = $token[2] + $lineOffset;
                continue;
            }
            if (in_array($token[0], [T_ABSTRACT, T_FINAL])) {
                $token = $this->nextToken($tokens, $parseContext); // Skip "abstract" and "final" keywords
            }
            if ($token[0] === T_CLASS) { // Doc-comment before a class?
                if (is_array($previousToken) && $previousToken[0] === T_DOUBLE_COLON) {
                    //php 5.5 class name resolution (i.e. ClassName::class)
                    continue;
                }
                $token = $this->nextToken($tokens, $parseContext);
                $definitionContext = new Context(['class' => $token[1], 'line' => $token[2]], $parseContext);
                if ($classDefinition) {
                    $analysis->addClassDefinition($classDefinition);
                }
                $classDefinition = [
                    'class' => $token[1],
                    'extends' => null,
                    'properties' => [],
                    'methods' => [],
                    'context' => $definitionContext
                ];
                // @todo detect end-of-class and reset $definitionContext
                $token = $this->nextToken($tokens, $parseContext);
                if ($token[0] === T_EXTENDS) {
                    $definitionContext->extends = $this->parseNamespace($tokens, $token, $parseContext);
                    $classDefinition['extends'] = $definitionContext->fullyQualifiedName($definitionContext->extends);
                }
                if ($comment) {
                    $definitionContext->line = $line;
                    $this->analyseComment($analysis, $analyser, $comment, $definitionContext);
                    $comment = false;
                    continue;
                }
            }
            if ($token[0] === T_TRAIT) {
                $classDefinition = false;
                $token = $this->nextToken($tokens, $parseContext);
                $definitionContext = new Context(['trait' => $token[1], 'line' => $token[2]], $parseContext);
                if ($comment) {
                    $definitionContext->line = $line;
                    $this->analyseComment($analysis, $analyser, $comment, $definitionContext);
                    $comment = false;
                    continue;
                }
            }
            if ($token[0] === T_STATIC) {
                $token = $this->nextToken($tokens, $parseContext);
                if ($token[0] === T_VARIABLE) { // static property
                    $propertyContext = new Context([
                        'property' => substr($token[1], 1),
                        'static' => true,
                        'line' => $line
                            ], $definitionContext);
                    if ($classDefinition) {
                        $classDefinition['properties'][$propertyContext->property] = $propertyContext;
                    }
                    if ($comment) {
                        $this->analyseComment($analysis, $analyser, $comment, $propertyContext);
                        $comment = false;
                    }
                    continue;
                }
            }

            if (in_array($token[0], [T_PRIVATE, T_PROTECTED, T_PUBLIC, T_VAR])) { // Scope
                $token = $this->nextToken($tokens, $parseContext);
                if ($token[0] == T_STATIC) {
                    $token = $this->nextToken($tokens, $parseContext);
                }
                if ($token[0] === T_VARIABLE) { // instance property
                    $propertyContext = new Context([
                        'property' => substr($token[1], 1),
                        'line' => $line
                            ], $definitionContext);
                    if ($classDefinition) {
                        $classDefinition['properties'][$propertyContext->property] = $propertyContext;
                    }
                    if ($comment) {
                        $this->analyseComment($analysis, $analyser, $comment, $propertyContext);
                        $comment = false;
                    }
                } elseif ($token[0] === T_FUNCTION) {
                    $token = $this->nextToken($tokens, $parseContext);
                    if ($token[0] === T_STRING) {
                        $methodContext = new Context([
                            'method' => $token[1],
                            'line' => $line
                                ], $definitionContext);
                        if ($classDefinition) {
                            $classDefinition['methods'][$token[1]] = $methodContext;
                        }
                        if ($comment) {
                            $this->analyseComment($analysis, $analyser, $comment, $methodContext);
                            $comment = false;
                        }
                    }
                }
                continue;
            } elseif ($token[0] === T_FUNCTION) {
                $token = $this->nextToken($tokens, $parseContext);
                if ($token[0] === T_STRING) {
                    $methodContext = new Context([
                        'method' => $token[1],
                        'line' => $line
                            ], $definitionContext);
                    if ($classDefinition) {
                        $classDefinition['methods'][$token[1]] = $methodContext;
                    }
                    if ($comment) {
                        $this->analyseComment($analysis, $analyser, $comment, $methodContext);
                        $comment = false;
                    }
                }
            }
            if (in_array($token[0], [T_NAMESPACE, T_USE]) === false) { // Skip "use" & "namespace" to prevent "never imported" warnings)
                // Not a doc-comment for a class, property or method?
                if ($comment) {
                    $this->analyseComment($analysis, $analyser, $comment, new Context(['line' => $line], $definitionContext));
                    $comment = false;
                }
            }
            if ($token[0] === T_NAMESPACE) {
                $parseContext->namespace = $this->parseNamespace($tokens, $token, $parseContext);
                continue;
            }
            if ($token[0] === T_USE) {
                $statements = $this->parseUseStatement($tokens, $token, $parseContext);
                foreach ($statements as $alias => $target) {
                    if ($target[0] === '\\') {
                        $target = substr($target, 1);
                    }

                    $parseContext->uses[$alias] = $target;
                    foreach (Analyser::$whitelist as $namespace) {
                        if (strcasecmp(substr($target, 0, strlen($namespace)), $namespace) === 0) {
                            $imports[strtolower($alias)] = $target;
                            break;
                        }
                    }
                }
                $analyser->docParser->setImports($imports);
                continue;
            }
        }
        if ($comment) { // File ends with a T_DOC_COMMENT
            $this->analyseComment($analysis, $analyser, $comment, new Context(['line' => $line], $definitionContext));
        }
        if ($classDefinition) {
            $analysis->addClassDefinition($classDefinition);
        }
        return $analysis;
    }
    /**
     *
     * @param Analysis $analysis
     * @param Analyser $analyser
     * @param string $comment
     * @param Context $context
     */
    private function analyseComment($analysis, $analyser, $comment, $context)
    {
        $analysis->addAnnotations($analyser->fromComment($comment, $context), $context);
    }

    /**
     * The next non-whitespace, non-comment token.
     *
     * @param array $tokens
     * @param Context $context
     * @return string|array The next token (or false)
     */
    private function nextToken(&$tokens, $context)
    {
        $token = next($tokens);
        if ($token[0] === T_WHITESPACE) {
            return $this->nextToken($tokens, $context);
        }
        if ($token[0] === T_COMMENT) {
            $pos = strpos($token[1], '@SWG\\');
            if ($pos) {
                $line = $context->line ? $context->line + $token[2] : $token[2];
                $commentContext = new Context(['line' => $line], $context);
                Logger::notice('Annotations are only parsed inside `/**` DocBlocks, skipping ' . $commentContext);
            }
            return $this->nextToken($tokens, $context);
        }
        return $token;
    }

    private function parseNamespace(&$tokens, &$token, $parseContext)
    {
        $namespace = '';
        while ($token !== false) {
            $token = $this->nextToken($tokens, $parseContext);
            if ($token[0] !== T_STRING && $token[0] !== T_NS_SEPARATOR) {
                break;
            }
            $namespace .= $token[1];
        }
        return $namespace;
    }

    private function parseUseStatement(&$tokens, &$token, $parseContext)
    {
        $class = '';
        $alias = '';
        $statements = [];
        $explicitAlias = false;
        while ($token !== false) {
            $token = $this->nextToken($tokens, $parseContext);
            $isNameToken = $token[0] === T_STRING || $token[0] === T_NS_SEPARATOR;
            if (!$explicitAlias && $isNameToken) {
                $class .= $token[1];
                $alias = $token[1];
            } elseif ($explicitAlias && $isNameToken) {
                $alias .= $token[1];
            } elseif ($token[0] === T_AS) {
                $explicitAlias = true;
                $alias = '';
            } elseif ($token === ',') {
                $statements[$alias] = $class;
                $class = '';
                $alias = '';
                $explicitAlias = false;
            } elseif ($token === ';') {
                $statements[$alias] = $class;
                break;
            } else {
                break;
            }
        }
        return $statements;
    }
}
