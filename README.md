PHP-Brainfuck
=============

A PHP interpreter for Brainfuck.

## Hello world
An example for printing out Hello world with Brainfuck

```php
<?php

include 'interpreter.php';

// Create instance
$program = BrainfuckInstance::getInstance();

// Execute operations from file
$program->invokeFile('helloworld.bf');

// Execute operations from string
$program->invokeCommand('++');

// Print out program output
echo $program->getOutputBuffer();
```
