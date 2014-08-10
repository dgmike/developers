<?php

class CashMachineTest
    extends PHPUnit_Framework_TestCase
{
    public $instance;

    public function setUp()
    {
        $this->instance = new CashMachine\CashMachine;
    }

    public function testDefaultMoney()
    {
        $expected = array(100, 50, 20, 10);
        $response = $this->instance->getBankNotes();
        $this->assertEquals($expected, $response);
    }

    public function testChangeMoney()
    {
        $expected = array(20, 10);
        $this->instance->setBankNotes(array(10, 20));
        $response = $this->instance->getBankNotes();
        $this->assertEquals($expected, $response);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value must be major than zero
     */
    public function testNegativeNumberMustBeException()
    {
        $this->instance->withDraw(-100);
    }

    /**
     * @dataProvider invalidArguments
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value must be major than zero
     */
    public function testNotNumberMustBeException($value)
    {
        $this->instance->withDraw($value);
    }

    public function invalidArguments()
    {
        return array(
            array(new stdClass),
            array(array()),
            array(true),
            array(false),
            array('A'),
            array(''),
        );
    }

    /**
     * @dataProvider validValues
     */
    public function testValidResults($value, $expected)
    {
        $result = $this->instance->withDraw($value);
        $this->assertEquals($expected, $result);
    }

    public function validValues()
    {
        return array(
            array(0, array()),
            array(10, array(10)),
            array(20, array(20)),
            array(30, array(20, 10)),
            array(40, array(20, 20)),
            array(50, array(50)),
            array(60, array(50, 10)),
            array(70, array(50, 20)),
            array(80, array(50, 20, 10)),
            array(80.0, array(50, 20, 10)),
        );
    }

    public function testEmptyArrayWhenTrNullValue()
    {
        $result = $this->instance->withDraw(null);
        $this->assertEquals(array(), $result);
    }

    /**
     * @expectedException CashMachine\NoteUnavailableException
     * @expectedExceptionMessage Can't parse value with avaliable banknotes
     */
    public function testInvalidValueMustBeException()
    {
        $result = $this->instance->withDraw(1);
    }

    /**
     * @expectedException CashMachine\NoteUnavailableException
     * @expectedExceptionMessage Can't parse value with avaliable banknotes
     */
    public function testInvalidValueMustBeException2()
    {
        $result = $this->instance->withDraw(10.2);
    }

    public function testWithCustomBankNotes()
    {
        $this->instance->setBankNotes(array(5, 20, 50));
        $expected = array(50, 20, 20, 5);
        $result = $this->instance->withDraw(95);
        $this->assertEquals($expected, $result);
    }
}
