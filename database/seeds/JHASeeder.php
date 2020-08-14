<?php

use App\JHA;
use App\User;
use App\Department;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class JHASeeder extends Seeder
{
  private $departments;

  private $amount = 15;

  private $faker;

  private $supervisors;

  private $employees;

  private $ppe = [
    'glasses',
    'gloves',
    'steel toe boots',
    'face mask',
    'resperator',
    'fire-proof clothing',
    'safety harness',
    'spotter'
  ];

  private $training = [
    'forklift',
    'firewatch',
    'basic safety',
    'ppe',
    'serve safe',
    'network+'
  ];

  public function __construct() 
  {
    $this->faker       = Faker::create();
    $this->departments = Department::all();
    $this->supervisors = collect();
    $this->employees   = collect();
    $this->ppe         = collect($this->ppe);
    $this->training    = collect($this->training);

    $seeder = $this;

    User::all()->each(function($u) use ($seeder) {
      if($u->isSupervisor()) 
        $seeder->supervisors->push($u);
      else
        $seeder->employees->push($u);
    });
  } 

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->createJHAs($this->amount);
  }

  private function createJHAs($amount = 0) 
  {
    for($i = 0; $i < $amount; $i++) {
      $dept = $this->departments->random(); 
      $super = $this->supervisors->random();
      $employee = $this->employees->random();

      $created_at = now()->subDays(rand(80, 200));
      $updated_at = $created_at->addDays(rand(1, 20));

      $jha = [
        'department_id'  => $dept->id,
        'supervisor_id'  => $super->id,
        'prepared_by_id' => $employee->id,
        'task_name'      => $this->faker->sentence,
        'ppe'            => $this->roleForPPE(),
        'training'       => $this->roleForTraining(),
        'steps'          => $this->roleForSteps(),
        'approved_at'    => $this->roleForApproved(),
        'reviewed_at'    => $this->roleForReviewed(),
        'created_at'     => $created_at,
        'updated_at'     => $updated_at
      ];

      if(! is_null($jha['approved_at']))
        $jha['approved_by_id'] = $this->supervisors->random()->id;

      if(! is_null($jha['reviewed_at']))
        $jha['reviewed_by_id'] = $this->supervisors->random()->id;

      JHA::create($jha);
    }
  } 

  private function roleForPPE() 
  {
    $amount = $this->role(0, $this->ppe->count() - 1);

    return $this->ppe->shuffle()->take($amount);
  } 

  private function roleForTraining() 
  {
    $amount = $this->role(0, $this->training->count() - 1);

    return $this->training->shuffle()->take($amount);
  } 

  private function roleForSteps() 
  {
    $amount = $this->role(0, 6);

    if($amount === 0)
      return collect();

    $seeder = $this;

    return collect(array_fill(0, $amount, null))->map(function() use ($seeder) {
      $hazards_roll = $seeder->role(0, 5);
      $control_roll = $seeder->role(0, 5);

      if($hazards_roll === 0)
        $hazards = collect();
      else
        $hazards = collect(array_fill(0, $hazards_roll, ''))->map(function() use ($seeder) {
          return $seeder->faker->sentence;
        });

      if($control_roll === 0)
        $control = collect();
      else
        $control = collect(array_fill(0, $control_roll, ''))->map(function() use ($seeder) {
          return $seeder->faker->sentence;
        });

      return [
        'task'     => $seeder->faker->sentence,
        'hazards'  => $hazards,
        'controls' => $control,
      ];
    });
  } 

  private function roleForApproved() 
  {
    if($this->role(0,10) > 5)
      return now()->subDays(rand(2, 50));

    return null;
  } 

  private function roleForReviewed() 
  {
    if($this->role(0,10) > 5)
      return now()->subDays(rand(2, 20));

    return null;
  } 

  private function role($min = 0, $max = 1) 
  {
    return rand($min, $max);
  } 
}
