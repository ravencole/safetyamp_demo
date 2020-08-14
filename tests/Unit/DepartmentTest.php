<?php

namespace Tests\Unit;

use App\User;
use App\Department;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
  /**
   * @test
   *
   */
  public function a_department_has_many_users() 
  {
    $department = factory(Department::class)->create();

    factory(User::class, 5)->create([
      'department_id' => $department->id
    ]);

    $this->assertCount(5, $department->users);
  }

  /**
   * @test
   *
   */
  public function a_department_has_many_employees() 
  {
    $department = factory(Department::class)->create();
    
    factory(User::class, 5)->create([
      'department_id' => $department->id,
      'role' => 'employee'
    ]);

    factory(User::class, 2)->create([
      'department_id' => $department->id,
      'role' => 'supervisor'
    ]);

    $this->assertCount(5, $department->employees);
  }


  /**
   * @test
   *
   */
  public function a_department_has_many_supervisors() 
  {
    $department = factory(Department::class)->create();
    
    factory(User::class, 5)->create([
      'department_id' => $department->id,
      'role' => 'employee'
    ]);

    factory(User::class, 2)->create([
      'department_id' => $department->id,
      'role' => 'supervisor'
    ]);

    $this->assertCount(2, $department->supervisors);
  }
}
