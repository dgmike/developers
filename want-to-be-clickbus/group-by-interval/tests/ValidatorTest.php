<?php

require __DIR__ . '/../vendor/autoload.php';

class ValidationTest
    extends PHPUnit_Framework_TestCase
{
    public $instance;

    public function setUp()
    {
        $this->instance = new GroupByInterval\Validator;
    }

    public function testValidateOneItem()
    {
        $this->assertTrue(method_exists($this->instance, 'isInteger'));
    }

    /**
     * @dataProvider validIntegers
     */
    public function testValidateInteger($value)
    {
        $isValid = $this->instance->isInteger($value);
        $this->assertTrue($isValid);
    }

    public function validIntegers()
    {
        return array(
            array(1),
            array(2),
            array(-1),
            array(0),
            array(1.0),
            array('1.0'),
            array('1'),
        );
    }

    /**
     * @dataProvider invalidIntegers
     */
    public function testValidateNotInteger($value)
    {
        $isValid = $this->instance->isInteger($value);
        $this->assertFalse($isValid);
    }

    public function invalidIntegers()
    {
        return array(
            array(null),
            array('A'),
            array(''),
            array(false),
            array(true),
            array(1.2),
            array('1.2'),
            array(array()),
            array(new stdClass()),
        );
    }

    public function testValidateValidArray()
    {
        $values = array(1, 2, 3, 4, '5', 6.0);
        $isValid = $this->instance->validate($values, 'isInteger');
        $this->assertTrue($isValid);
    }

    public function testValidateInvalidArray()
    {
        $values = array(1, 'A', 3, 4, '5', 6.0);
        $isValid = $this->instance->validate($values, 'isInteger');
        $this->assertFalse($isValid);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid validation name
     */
    public function testInvalidTestName()
    {
        $this->instance->validate(array(), 'invalidValidation');
    }
}
