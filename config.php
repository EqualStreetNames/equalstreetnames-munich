<?php

declare(strict_types=1);

return [
    'relationId' => 54388,

    'languages' => ['de', 'en'],

    'exclude' => [
        'relation' => [],
        'way' => [],
    ],

    'gender' => [
    ],

    'instances' => [
        'Q5'        => true,  // human
        'Q2985549'  => true,  // mononymous person
        'Q20643955' => true,  // human biblical figure

        'Q8436'     => false, // family
        'Q101352'   => false, // family name
        'Q327245'   => false, // team
        'Q3046146'  => false, // married couple
        'Q13417114' => false, // noble family
    ],
];
