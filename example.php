<?php

/**
 * ----------------------------------------
 * Example - PHP Brainfuck Interpreter
 * ----------------------------------------
 * https://github.com/SteveWinfield/PHP-Brainfuck
**/
include 'interpreter.php';

// Create instance
$program = BrainfuckInstance::getInstance();

// Execute operations from file
$program->invokeFile('helloworld.bf');

// Execute operations from string
$program->invokeCommand('++');

// Print out program output
echo $program->getOutputBuffer();