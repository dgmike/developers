<?php

namespace GroupByInterval;
use InvalidArgumentException;

class GroupByInterval
{
    public $values = array();
    public $range = null;

    public function validateRange(array $values)
    {
        $validator = new Validator();
        $isValid = $validator->validate($values, 'isInteger');
        if (!$isValid) {
            throw new InvalidArgumentException('Invalid argument. All itens in array must be integers', 1);
        }
        return true;
    }

    public function setValues(array $values) {
        $this->validateRange($values);
        $this->values = $values;
    }

    public function setRange($range) {
        $this->range = $range;
    }

    public function getGroups() {
        if (!$this->range) {
            return array();
        }

        $values = $this->_bubbleSort($this->values);
        $intervals = $this->_intervals($values);
        $groups = $this->_arrange($values, $intervals);

        return array_values($groups);
    }

    // facade method ;)

    public function parseGroupsByRange($range, $numbers) {
        $this->setRange($range);
        $this->setValues($numbers);
        return $this->getGroups();
    }

    // PRIVATE METHODS

    private function _bubbleSort(array $values, $comparator = '>')
    {
        $loops = count($values);
        while ($loops--) {
            for ($iterator = 0; $iterator < $loops; $iterator++) {
                if (version_compare($values[$iterator], $values[$iterator+1], $comparator)) {
                    list($values[$iterator], $values[$iterator + 1]) = array($values[$iterator + 1], $values[$iterator]);
                }
            }
        }

        return $values;
    }

    private function _intervals($values)
    {
        $intervalsNegative = array();
        if (min($values) < 0) {
            $intervalsNegative = range(0, min($values) - $this->range, $this->range);
            $intervalsNegative = $this->_bubbleSort($intervalsNegative, '<');
        }

        $intervalsPositive = range(0, max($values) + $this->range, $this->range);

        $intervals = array_merge($intervalsNegative, $intervalsPositive);

        return $intervals;
    }

    private function _whereToPut($value, $intervals)
    {
        foreach ($intervals as $index => $greatherThan) {
            $minorThan = isset($intervals[$index + 1]) ? $intervals[$index + 1] : $greatherThan;
            if ($value > $greatherThan && $value <= $minorThan) {
                $putOn = $greatherThan;
            }
        }
        return $putOn;
    }

    private function _arrange($values, $intervals)
    {
        $groups = array();
        foreach ($values as $value) {
            $putOn = $this->_whereToPut($value, $intervals);
            if (!isset($groups[$putOn])) {
                $groups[$putOn] = array();
            }
            array_push($groups[$putOn], $value);
        }
        return $groups;
    }

}
