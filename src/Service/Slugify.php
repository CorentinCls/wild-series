<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input): string
    {
       $modif = str_replace(' ', '-', $input);

       return $modif;
    }
}

