<?php

namespace Test;

use Dev\InputParserService;

class InputParserServiceTest extends \PHPUnit_Framework_TestCase
{
	private $inputParser;

    public function setUp()
    {
        $this->inputParser = new InputParserService();
    }

    public function tearDown()
    {
        $this->inputParser = null;
    }

    public function testGetNumbersWithNonStringParameterThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inputParser->getNumbers(3);
    }

    public function testGetNumbersWithNonNumbersThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inputParser->getNumbers('3,f');
    }

    public function testGetNumbersAcceptsNewLineAsASeparator()
    {
        $expected = $this->inputParser->getNumbers('4\n3,5');
        $this->assertSame(['4','3','5'], $expected, 'getNumbers does not accept new line character as separator');
    }

    public function testGetNumbersAllowsDifferentDelimiters()
    {
        $expected = $this->inputParser->getNumbers('\\;\\12;23;2;34');
        $this->assertSame(['12','23','2','34'], $expected, 'getNumbers does not accept user defined delimiter');
    }

    public function testGetNumbersAllowsDifferentDelimitersAlongWithNewLine()
    {
        $expected = $this->inputParser->getNumbers('\\;\\1;2;3\n4');
        $this->assertSame(['1','2','3','4'], $expected, 'getNumbers does not accept user defined delimiter with new line');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNumbersDoesNotAllowNegativeNumbers()
    {
        $this->inputParser->getNumbers('\\;\\12;5;-4;6');
    }

    public function testGetNumbersIgnoresNumbersAboveOneThousand()
    {
        $result = $this->inputParser->getNumbers('\\(\\2(23(1001(25');
        $this->assertSame(['2','23','25'], $result, 'getNumbers does not ignore numbers above one thousand');
    }
}