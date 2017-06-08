<?php

namespace Dev;

use Prophecy\Exception\Exception;
use Dev\InputParserService;

class Calculator 
{

	private $inputParser;

	public function __construct()
	{
		$this->inputParser = new InputParserService();
	}

	public function add($numbers = '')
	{
		$numberArray = $this->inputParser->getNumbers($numbers);
		return array_sum($numberArray);
	}

	public function multiply($numbers = '')
	{
		
		$numberArray = $this->inputParser->getNumbers($numbers);

		return array_reduce($numberArray, function($carry, $item) {
			$carry = $carry*$item;
			return $carry;
		}, 1);
	}

}