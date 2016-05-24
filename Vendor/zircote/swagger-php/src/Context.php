<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

/**
 * Context
 *
 * The context in which the annotation is parsed.
 * It includes useful metadata which the Processors can use to augment the annotations.
 *
 * Context hierarchy:
 * - parseContext
 *   |- docBlockContext
 *   |- classContext
 *      |- docBlockContext
 *      |- propertyContext
 *      |- methodContext
 *
 * @property string $comment  The PHP DocComment
 * @property string $filename
 * @property int $line
 * @property int $character
 *
 * @property string $namespace
 * @property array $uses
 * @property string $class
 * @property string $extends
 * @property string $method
 * @property string $property
 * @property Annotations\AbstractAnnotation[] $annotations
 */
class Context
{
    /**
     * Prototypical inheritance for properties.
     * @var Context
     */
    private $_parent;

    /**
     * @param array $properties new properties for this context.
     * @param Context $parent The parent context
     */
    public function __construct($properties = [], $parent = null)
    {
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
        $this->_parent = $parent;
    }

    /**
     * Check if a property is set directly on this context and not its parent context.
     *
     * @param string $type Example: $c->is('method') or $c->is('class')
     * @return bool
     */
    public function is($type)
    {
        return property_exists($this, $type);
    }
    
    /**
     * Check if a property is NOT set directly on this context and but its parent context.
     *
     * @param string $type Example: $c->not('method') or $c->not('class')
     * @return bool
     */
    public function not($type)
    {
        return property_exists($this, $type) === false;
    }

    /**
     * Return the context containing the specified property.
     *
     * @param string $property
     * @return boolean|\Swagger\Context
     */
    public function with($property)
    {
        if (property_exists($this, $property)) {
            return $this;
        }
        if ($this->_parent) {
            return $this->_parent->with($property);
        }
        return false;
    }

    /**
     * @return \Swagger\Context
     */
    public function getRootContext()
    {
        if ($this->_parent) {
            return $this->_parent->getRootContext();
        }
        return $this;
    }

    /**
     * Export location for debugging.
     *
     * @return string Example: "file1.php on line 12"
     */
    public function getDebugLocation()
    {
        $location = '';
        if ($this->class && ($this->method || $this->property)) {
            $location .= $this->fullyQualifiedName($this->class);
            if ($this->method) {
                $location .= ($this->static ? '::' : '->') . $this->method . '()';
            } elseif ($this->property) {
                $location .= ($this->static ? '::$' : '->') . $this->property;
            }
        }
        if ($this->filename) {
            if ($location !== '') {
                $location .= ' in ';
            }
            $location .= $this->filename;
        }
        if ($this->line) {
            if ($location !== '') {
                $location .= ' on';
            }
            $location .= ' line ' . $this->line;
            if ($this->character) {
                $location .= ':' . $this->character;
            }
        }
        return $location;
    }

    /**
     * Traverse the context tree to get the property value.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->_parent) {
            return $this->_parent->$property;
        }
        return null;
    }

    public function __toString()
    {
        return $this->getDebugLocation();
    }

    public function __debugInfo()
    {
        return ['-' => $this->getDebugLocation()];
    }

    /**
     * @return string|null
     */
    public function extractDescription()
    {
        $lines = explode("\n", $this->comment);
        unset($lines[0]);
        $description = '';
        foreach ($lines as $line) {
            $line = ltrim($line, "\t *");
            if (substr($line, 0, 1) === '@') {
                break;
            }
            $description .= $line . ' ';
        }
        $description = trim($description);
        if ($description === '') {
            return null;
        }
        if (stripos($description, 'license')) {
            return null; // Don't use the GPL/MIT license text as the description.
        }
        return $description;
    }

    /**
     * Create a Context based on the debug_backtrace
     * @param int $index
     * @return \Swagger\Context
     */
    public static function detect($index = 0)
    {
        $context = new Context();
        $backtrace = debug_backtrace();
        $position = $backtrace[$index];
        if (isset($position['file'])) {
            $context->filename = $position['file'];
        }
        if (isset($position['line'])) {
            $context->line = $position['line'];
        }
        $caller = @$backtrace[$index + 1];
        if (isset($caller['function'])) {
            $context->method = $caller['function'];
            if (isset($caller['type']) && $caller['type'] === '::') {
                $context->static = true;
            }
        }
        if (isset($caller['class'])) {
            $fqn = explode('\\', $caller['class']);
            $context->class = array_pop($fqn);
            if (count($fqn)) {
                $context->namespace = implode('\\', $fqn);
            }
        }
        // @todo extract namespaces and use statements
        return $context;
    }

    /**
     * Resolve the fully qualified name.
     *
     * @param string $class  The class name
     * @return string
     */
    public function fullyQualifiedName($class)
    {
        if ($this->namespace) {
            $namespace = str_replace('\\\\', '\\', '\\' . $this->namespace . '\\');
        } else {
            $namespace = '\\'; // global namespace
        }
        if (strcasecmp($class, $this->class) === 0) {
            return $namespace . $this->class;
        }
        $pos = strpos($class, '\\');
        if ($pos !== false) {
            if ($pos === 0) {
                // Fully qualified name (\Foo\Bar)
                return $class;
            }
            // Qualified name (Foo\Bar)
            if ($this->uses) {
                foreach ($this->uses as $alias => $aliasedNamespace) {
                    $alias .= '\\';
                    if (strcasecmp(substr($class, 0, strlen($alias)), $alias) === 0) {
                        // Aliased namespace (use \Long\Namespace as Foo)
                        return '\\' . $aliasedNamespace . substr($class, strlen($alias) - 1);
                    }
                }
            }
        } elseif ($this->uses) {
            // Unqualified name (Foo)
            foreach ($this->uses as $alias => $aliasedNamespace) {
                if (strcasecmp($alias, $class) === 0) {
                    return '\\' . $aliasedNamespace;
                }
            }
        }
        return $namespace . $class;
    }
}
