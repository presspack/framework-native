<?php

$finder = Symfony\Component\Finder\Finder::create()
  ->notPath('bootstrap')
  ->notPath('storage')
  ->notPath('vendor')
  ->in(__DIR__)
  ->name('*.php')
  ->name('_ide_helper')
  ->notName('*.blade.php')
  ->ignoreDotFiles(true)
  ->ignoreVCS(true);


return PhpCsFixer\Config::create()
  ->setRiskyAllowed(true)
  ->setRules([
    '@Symfony' => true,
    '@PSR1' => true,
    '@PSR2' => true,
    '@Symfony:risky' => true,
    'array_syntax' => ['syntax' => 'short'],
    'combine_consecutive_unsets' => true,
    'list_syntax' => ['syntax' => 'long'],
    'no_extra_blank_lines' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'],
    'no_short_echo_tag' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'php_unit_strict' => true,
    'php_unit_test_class_requires_covers' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'semicolon_after_instruction' => true,
    'strict_comparison' => false,
    'strict_param' => true,
    'no_superfluous_phpdoc_tags' => true,
  ])
  ->setFinder($finder);
