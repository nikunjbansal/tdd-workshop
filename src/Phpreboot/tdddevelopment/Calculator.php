<?php

namespace Dev;

use Prophecy\Exception\Exception;

class Calculator 
{

	private $delimiter = ',';

	private function parseInput($numbers)
	{
		if(preg_match('/(\\\\)(.)\1/', $numbers, $delimiter)) {

			$this->delimiter = $delimiter[2];

			$numbers = preg_replace('/(\\\\)(.)\1/', '', $numbers);
		}
		if(stripos($numbers, '\n') !== false) {
			$numbers = str_replace('\n', $this->delimiter, $numbers);
		}

		return $numbers;
	}

	/**
	 * @param  String $numbers
	 * @return Array|Boolean
	 */
	private function validateInputNumbers($numbers)
	{

		$negativeNumbers = [];
		$validNumbers = [];

		if(!is_string($numbers)) {
			throw new \InvalidArgumentException('Input Parameter must be a string');
		}

		$numbersArray = explode($this->delimiter, $numbers);

		foreach ($numbersArray as $number) {
			if(!is_numeric($number)) {
				throw new NotANumberException('Negative Numbers not allowed');
			}
			if($number < 0) {
				array_push($negativeNumbers, $number);
			}
			if($number <= 1000) {
				array_push($validNumbers, $number);
			}
		}
		
		if(count($negativeNumbers) > 0) {
			throw new NegativeNumberException("Negative Numbers (".implode(",", $negativeNumbers).") not allowed");
		}
		
		return count($validNumbers > 0) ? $validNumbers : False;

	}

	public function add($numbers = '')
	{

		$numbers = $this->parseInput($numbers);

		if($numbers === '') {
			return 0;
		}

		try {
			$validNumbersArray = $this->validateInputNumbers($numbers);
		} catch (\InvalidArgumentException $e) {
			throw new \InvalidArgumentException($e->getMessage());
		}

		return array_sum($validNumbersArray);
	}

	public function multiply($numbers = '')
	{
		$numbers = $this->parseInput($numbers);
		
		if($numbers === '') {
			return 0;
		}

		try {
			$validNumbersArray = $this->validateInputNumbers($numbers);
		} catch (\InvalidArgumentException $e) {
			throw new \InvalidArgumentException($e->getMessage());
		}

		return array_reduce($validNumbersArray, function($carry, $item) {
			$carry = $carry*$item;
			return $carry;
		}, 1);
	}

}

class NotANumberException extends \InvalidArgumentException {}
class NegativeNumberException extends \InvalidArgumentException {}