<?php

namespace Tests\Unit;

use App\Department;
use Tests\TestCase;

class UserTest extends TestCase
{
  /**
   * @test
   *
   */
  public function a_user_has_a_department() 
  {
    $user = $this->createUser();

    $this->assertNotNull($user->department);
  }

  /**
   * @test
   *
   */
  public function a_user_has_a_role() 
  {
    $employee = $this->createEmployee();
    $this->assertEquals('employee', $employee->role);

    $supervisor = $this->createSupervisor();
    $this->assertEquals('supervisor', $supervisor->role);
  }

  /**
   * @test
   *
   */
  public function a_user_belongs_to_a_department() 
  {
    $employee = $this->createEmployee();

    $this->assertEquals(Department::class, get_class($employee->department));
  }
}
