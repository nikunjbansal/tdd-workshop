<?php

namespace Dev;

use Prophecy\Exception\Exception;

class InputParserService
{

	/**
	 * @param  String $numberString 
	 * @return Array      
	 */
	private function parseNumbersFromInput($input)
	{
		$delimiter = ',';
		if(preg_match('/(\\\\)(.)\1/', $input, $match)) {
			$delimiter = $match[2];
			$input = preg_replace('/(\\\\)+(.)(\\\\)+/', '', $input);
		}
		if(stripos($input, '\n') !== false) {
			$input = str_replace('\n', $delimiter, $input);
		}

		return ($input == '') ? [0] : explode($delimiter, $input);
	}

	/**
	 * @param  Array $numbers 
	 * @return Array 
	 * @throws \InvalidArgumentException
	 */
	private function validateNumbers($numbersArray)
	{
		$negativeNumbers = [];
		$validNumbers = [];
		foreach ($numbersArray as $number) {
			if(!is_numeric($number)) {
				throw new \InvalidArgumentException('Only Numbers are allowed');
			}

			if($number < 0) {
				array_push($negativeNumbers, $number);
			}
			if($number <= 1000) {
				array_push($validNumbers, $number);
			}
		}
		
		if(count($negativeNumbers) > 0) {
			throw new \InvalidArgumentException("Negative Numbers (".implode(",", $negativeNumbers).") not allowed");
		}

		return $validNumbers;
	}

	/**
	 * @param  String $numberString
	 * @return Array 
	 * @throws \InvalidArgumentException
	 */
	public function getNumbers($numberString)
	{
		if(!is_string($numberString)) {
			throw new \InvalidArgumentException('Input Parameter must be a string');
		}

		$numbersArray = $this->parseNumbersFromInput($numberString);

		try {
			$validNumbers = $this->validateNumbers($numbersArray);
		} catch (\InvalidArgumentException $e) {
			throw new \InvalidArgumentException($e->getMessage());
		}

		return $validNumbers;
	}
}