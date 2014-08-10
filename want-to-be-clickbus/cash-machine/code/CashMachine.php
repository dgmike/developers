<?php

namespace CashMachine;
use InvalidArgumentException;

class CashMachine
{
    protected $bankNotes = array(10, 20, 50, 100);

    public function setBankNotes(array $bankNotes)
    {
        $this->bankNotes = $bankNotes;
    }

    public function getBankNotes()
    {
        $bankNotes = $this->bankNotes;
        rsort($bankNotes);
        return $bankNotes;
    }

    public function withDraw($value)
    {
        if ($value === null) {
            return array();
        }

        if (!is_numeric($value) || $value < 0) {
            throw new InvalidArgumentException('Value must be major than zero', 1);
        }

        $withDraw = array();

        foreach ($this->getBankNotes() as $bankNote) {
            while ($value > 0 && $bankNote <= $value) {
                $withDraw[] = $bankNote;
                $value = $value - $bankNote;
            }
        }

        if ($value > 0) {
            throw new NoteUnavailableException('Can\'t parse value with avaliable banknotes', 1);
        }
        return $withDraw;
    }
}
