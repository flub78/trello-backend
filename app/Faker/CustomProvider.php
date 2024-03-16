<?php

namespace App\Faker;

use Faker\Provider\Base;

class CustomProvider extends Base
{
    /**
     * Return fake data for comma separated strings
     */
    public function csv_string(int $number)
    {
        $result = [];
        for ($i = 0; $i < $number; $i++) {
            $result[] = '"' . $this->generator->word . '"';
        }
        return implode(', ', $result);
        #return '"red", "green", "blue", "yellow"';
    }

    public function csv_int(int $number)
    {
        $result = [];
        for ($i = 0; $i < $number; $i++) {
            $result[] = $this->generator->numberBetween(1, 100);
        }
        return implode(', ', $result);
    }
}
