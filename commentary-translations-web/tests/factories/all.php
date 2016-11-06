<?php

$factory('App\User', [
    'full_name' => $faker->name,
    'email'     => $faker->email,
    'password'  => bcrypt('password')
]);
