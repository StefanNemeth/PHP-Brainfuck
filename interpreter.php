<?php

/**
 * ----------------------------------------
 * @title BrainfuckInterpreter
 * @desc Interpreter for Brainfuck
 * @author Steve Winfield
 * @copyright 2014 $AUTHOR$
 * @license see /LICENCE
 * ----------------------------------------
 * https://github.com/SteveWinfield/PHP-Brainfuck
**/
class BrainfuckInstance {
	public function getBuffer() {
		return $this->buffer;
	}
	
	public function clearBuffer() {
		$m = $this->buffer;
		$this->buffer = '';
		return $m;
	}
	
	function __construct() {
		$this->index = 0;
		$this->memory = array(0);
		$this->handler = array (
			'+' => function () {
				++$this->memory[$this->index];
			},
			'-' => function () {
				--$this->memory[$this->index]; 
			},
			'>' => function () {
				if (!isset($this->memory[++$this->index])) {
					$this->memory[$this->index] = 0;
				}
			},
			'<' => function () {
				if ($this->index - 1 >= 0) {
					--$this->index;
				}
			},
			'.' => function () {
				$this->buffer .= chr($this->memory[$this->index]);
			},
			',' => function () {
				fscanf(STDIN, '%c', $chr);
				$this->memory[$this->index] = ord($chr);
			}
		);
	}
	
	public function invokeCommand ($cmd) {
		$i = 0;
		$c = str_split($cmd);
		$l = count($c);
		for ($i = 0; $i < $l; $i++) {
			/*
			 * loop handling
			**/
			if ($c[$i] == '[') {
				$innerCmd = '';
				for ($n = $i + 1; $n < $l; ++$n) {
					++$i;
					if ($c[$n] == ']') {
						break;
					}
					$innerCmd .= $c[$n];
				}
				while ($this->memory[$this->index] > 0) {
					$this->invokeCommand ($innerCmd);
				}
			}
			/**
			 * operation handling
			**/
			if (isset($this->handler[$c[$i]])) {
				$this->handler[$c[$i]]();
			}
			//throw new RuntimeException ('Operation not valid.');
		}
	}
	
	public function invokeFile ($fileName) {
		$file = @file_get_contents($fileName);
		if ($file) {
			$this->invokeCommand ($file);
			return true;
		}
		throw new Exception ('File not found.');
		return false;
	}
	
	private $buffer;
	private $index;
	private $memory;
	private $handler;
	
	public static function getInstance () {
		return new BrainfuckInstance();
	}
}