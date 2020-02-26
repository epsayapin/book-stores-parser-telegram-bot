<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(ChcnnBookCode::class, function (Faker $faker) {
    $bookCodes = [
            //'2617830',
            '4052565',
            '4052340',
            '4052821',
            '4000529',
            '3978197',
            '4000527',
            '2113226',
            '3791720',
            //'3869778'
    ];

    $count = count($bookCodes);

    return $bookCodes[rand(0, $count - 1)];
    
});
