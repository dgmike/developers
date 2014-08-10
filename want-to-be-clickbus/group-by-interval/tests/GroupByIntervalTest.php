<?php

class GrouByIntervalTest
	extends PHPUnit_Framework_TestCase
{
    public $instance;

    public function setUp()
    {
        $this->instance = new GroupByInterval\GroupByInterval();
    }

    /**
     * @dataProvider methods
     */
    public function testMethods($method)
    {
        $this->assertTrue(method_exists($this->instance, $method), "assert that instance has '${method}' method");
    }

    public function methods()
    {
        return array(
            array('setValues'),
            array('setRange'),
            array('getGroups'),
            array('parseGroupsByRange'),
            array('validateRange'),
        );
    }

    public function testValidateRange()
    {
        $this->assertTrue($this->instance->validateRange(array(1, '2', '3.0')));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid argument. All itens in array must be integers
     */
    public function testInvalidRangeMustExcept()
    {
        $this->assertTrue($this->instance->validateRange(array(1, 'A', 2)));
    }

    public function testSetValues()
    {
        $values = array(1, 2, 3);
        $this->instance->setValues($values);
        $this->assertEquals($this->instance->values, $values);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid argument. All itens in array must be integers
     */
    public function testSetValuesValidates()
    {
        $this->assertTrue($this->instance->setValues(array(1, 'A', 2)));
    }

    public function testSetRange()
    {
        $range = 10;
        $this->instance->setRange($range);
        $this->assertEquals($range, $this->instance->range);
    }

    /**
     * @dataProvider rangeNumbers
     */
    public function testGetGroups($range, $numbers, $expected)
    {
        $this->instance->setRange($range);
        $this->instance->setValues($numbers);
        $groups = $this->instance->getGroups();
        $this->assertEquals($expected, $groups);
    }

    public function rangeNumbers()
    {
        return array(
            array(
                5, array(1, 2, 3, 4),
                array(array(1, 2, 3, 4))
            ),
            array(
                2, array(1, 2, 3, 4),
                array(array(1, 2), array(3, 4))
            ),
            array(
                10, array(1, 12, 5, 3),
                array(array(1, 3, 5), array(12))
            ),
            array(
                10, array(1, 12, 5, 3),
                array(array(1, 3, 5), array(12))
            ),
            array(
                10, array(10, 1, -20,  14, 99, 136, 19, 20, 117, 22, 93,  120, 131),
                array(array(-20), array(1, 10), array(14, 19, 20), array(22), array(93, 99), array(117, 120), array(131, 136))
            ),
            array(
                15, array(10, 1, -20,  14, 99, 136, 19, 20, 117, 22, 93, 120, 131),
                array(array(-20), array(1, 10, 14), array(19, 20, 22), array(93, 99), array(117, 120), array(131), array(136))
            ),
            array(null, array(), array())
        );
    }

    /**
     * @dataProvider rangeNumbers
     */
    public function testParseGroupsByRange($range, $numbers, $expected)
    {
        $this->assertEquals($expected, $this->instance->parseGroupsByRange($range, $numbers));
    }
}
