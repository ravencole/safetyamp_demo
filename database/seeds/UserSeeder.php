<?php

use App\User;
use App\Department;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  private $number_of_supers = 15;

  private $number_of_employees = 60;

  private $departments;

  private $faker;

  public function __construct() 
  {
    $this->departments = Department::all();
    $this->faker = \Faker\Factory::create();
  } 

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    for($i = 1; $i <= $this->number_of_supers; $i++) 
      $this->createUser('supervisor', 'super' . $i . '@gmail.com');

    for($i = 1; $i <= $this->number_of_employees; $i++) 
      $this->createUser('employee', 'employee' . $i . '@gmail.com');

    $this->createUser('supervisor', 'ravenscole+super@gmail.com');
    $this->createUser('employee', 'ravenscole+employee@gmail.com');
  }

  private function createUser($role, $email) 
  {
    return User::create([
      'name' => $this->faker->name,
      'email' => $email,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
      'department_id' => $this->departments->random()->id,
      'role' => $role
    ]);
  } 
}
