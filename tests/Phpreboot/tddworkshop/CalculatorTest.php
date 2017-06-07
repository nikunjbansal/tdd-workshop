<?php

namespace Test;

use Dev\Calculator;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
	private $calculator;

    public function setUp()
    {
        $this->calculator = new Calculator();
    }

    public function tearDown()
    {
        $this->calculator = null;
    }

    public function testAddReturnsAnInteger()
    {
        $result = $this->calculator->add();

        $this->assertInternalType('integer', $result, 'Result of `add` is not an integer.');
    }

    public function testAddWithEmptyStringReturnsZero()
    {
        $result = $this->calculator->add();

        $this->assertSame(0, $result, 'Calling Add with empty string does not return 0');
    }

    public function testAddWithSingleNumberReturnsSameNumber()
    {
        $result = $this->calculator->add('2');

        $this->assertSame(2, $result, 'Calling Add With One Number does not return same number');   
    }

    public function testAddWithTwoNumbersReturnsTheirSum()
    {
        $result = $this->calculator->add('2,3');

        $this->assertSame(5, $result, 'Add does not perform sum of two numbers');
    }
    
    public function testAddWithNonStringParameterThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->add(3);
    }

    public function testAddWithNonNumbersThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->add('3,f');
    }

    /**
     * @dataProvider additionProvider
     */
    public function testAddWithMultipleNumbersReturnsTheirSum($numbers, $result)
    {
        $expected = $this->calculator->add($numbers);
        $this->assertSame($result, $expected, 'Add does not perform sum for multiple numbers');
    }


    public function testAddAcceptsNewLineAsASeparator()
    {
        $expected = $this->calculator->add('4\n3,5');
        $this->assertSame(12, $expected, 'Add does not accept new line character as separator');
    }

    public function testAddAllowsDifferentDelimiters()
    {
        $expected = $this->calculator->add('\\;\\12;23');
        $this->assertSame(35, $expected, 'Add does not accept user defined delimiter');
    }

    public function testAddAllowsDifferentDelimitersAlongWithNewLine()
    {
        $expected = $this->calculator->add('\\;\\1;2;3\n4');
        $this->assertSame(10, $expected, 'Add does not accept user defined delimiter with new line');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddDoesNotAllowNegativeNumbers()
    {
        $this->calculator->add('\\;\\12;5;-4;6');
    }

    public function testAddIgnoresNumbersAboveOneThousand()
    {
        $result = $this->calculator->add('\\(\\2(23(1001(25');
        $this->assertSame(50, $result, 'Add does not ignore numbers above one thousand');
    }

    public function testMultiplyReturnsAnInteger()
    {
        $result = $this->calculator->multiply();
        $this->assertInternalType('integer', $result, 'Result of `Multiply` is not an integer');
    }

    public function testMultiplyWithMultipleNumbersReturnsTheirProduct()
    {
        $result = $this->calculator->multiply('\\%\\1%2%3%10');
        $this->assertSame(60, $result, '`Multiply` does not give correct multiplication result for multiple numbers');   
    }

    public function additionProvider()
    {
        return [
            ['1',1],
            ['1,2',3],
            ['2,3,4,5', 14],
            ['4,7,3,4,7,3,5,6,7,4,3,2,5,7,5,3,4,6,7,8,9,5,5,5,4,3,2', 133]
        ];
    }

}