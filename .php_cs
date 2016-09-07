<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
  ->exclude(['vendor', 'node_modules', 'storage'])
  ->in(__DIR__);

return Symfony\CS\Config\Config::create()
  ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
  ->fixers([
    '-psr0',
    'phpdoc_order',
    'short_array_syntax',
  ])
  ->finder($finder);
