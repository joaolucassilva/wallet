<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__ . '.php-cs-fixer.cache')
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'single_quote' => true,
    ])
    ->setFinder($finder);
