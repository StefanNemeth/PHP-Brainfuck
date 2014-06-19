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
	public function getOutputBuffer() {
		return $this->outputBuffer;
	}
	
	public function clearOutputBuffer() {
		$m = $this->outputBuffer;
		$this->outputBuffer = '';
		return $m;
	}
	
	public function getInputBuffer() {
		return $this->inputBuffer;
	}
	
	public function clearInputBuffer() {
		$m = $this->inputBuffer;
		$this->inputBuffer = '';
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
				$this->outputBuffer .= chr($this->memory[$this->index]);
			},
			',' => function () {
				$char = chr(0);
				if (strlen($this->inputBuffer) > 0) {
					$char = substr($this->inputBuffer, 0, 1);
					$this->inputBuffer = substr($this->inputBuffer, 1);
				} else {
					fscanf(STDIN, '%s', $result);
					if (($len = strlen($result)) > 0) {
						if ($len > 1) {
							$this->inputBuffer = substr($result, 1);
						}
						$char = $result[0];
					}
				}
				$this->memory[$this->index] = ord($char);
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
	
	private $outputBuffer;
	private $inputBuffer;
	private $index;
	private $memory;
	private $handler;
	
	public static function getInstance () {
		return new BrainfuckInstance();
	}
}