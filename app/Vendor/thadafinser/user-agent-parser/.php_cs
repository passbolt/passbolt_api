<?php

$finder = Symfony\CS\Finder\DefaultFinder::create();
$finder->in([
    __DIR__ . '/src',
    __DIR__ . '/tests/integration',
    __DIR__ . '/tests/unit'
]);

$config = Symfony\CS\Config\Config::create();
$config->setUsingCache(true);
$config->setUsingLinter(false);
$config->finder($finder);
$config->level(Symfony\CS\FixerInterface::PSR2_LEVEL);
$config->fixers([
	//symfony
	'concat_without_spaces',
	'double_arrow_multiline_whitespaces',
	'duplicate_semicolon',
	'empty_return',
	'extra_empty_lines',
	'include',
	'join_function',
	'multiline_array_trailing_comma',
	'namespace_no_leading_whitespace',
	'new_with_braces',
	'no_blank_lines_after_class_opening',
	'no_empty_lines_after_phpdocs',
	'object_operator',
	'operators_spaces',
	'phpdoc_indent',
	'phpdoc_params',
	'remove_leading_slash_use',
	'remove_lines_between_uses',
	'return',
	'single_array_no_trailing_comma',
	'single_blank_line_before_namespace',
	'spaces_before_semicolon',
	'spaces_cast',
	'standardize_not_equal',
	'ternary_spaces',
	'unused_use',
	'whitespacy_lines',
	
	//contrib
	'align_double_arrow',
	'align_equals',
	'concat_with_spaces',
	'multiline_spaces_before_semicolon',
	'no_blank_lines_before_namespace',
	'ordered_use',
	'short_array_syntax',
]);

return $config;
