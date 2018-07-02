<?php

namespace App;

class Helper
{
    static function grade($value)
    {
        if ($value >= 80) { return 'A'; }
        if ($value >= 70) { return 'B'; }
        if ($value >= 60) { return 'C'; }
        if ($value >= 50) { return 'D'; }
        return 'E';
    }
}