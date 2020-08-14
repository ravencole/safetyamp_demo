<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\JHA;
use App\User;
use App\Department;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(JHA::class, function (Faker $faker) {
  $ppe = collect(array_fill(0, rand(1,6), ''))->map(function() use ($faker) {
    return $faker->word;
  });

  $training = collect(array_fill(0, rand(1,6), ''))->map(function() use ($faker) {
    return $faker->word;
  });

  $steps = collect(array_fill(0, rand(1,6), ''))->map(function() use ($faker) {
    $hazards = collect(array_fill(0, rand(1,3), ''))->map(function() use ($faker) {
      return $faker->sentence;
    });

    $controls = collect(array_fill(0, rand(1,3), ''))->map(function() use ($faker) {
      return $faker->sentence;
    });

    return [
      'task' => $faker->word,
      'hazards' => $hazards,
      'controls' => $controls,
    ];
  });

  return [
    'department_id' => factory(Department::class)->create()->id,
    'supervisor_id' => factory(User::class)->state('supervisor')->create()->id,
    'prepared_by_id' => factory(User::class)->state('employee')->create()->id,
    'task_name' => $faker->sentence,
    'ppe' => $ppe,
    'training' => $training,
    'steps' => $steps
  ];
});
