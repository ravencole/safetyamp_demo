<?php

namespace Tests\Unit;

use App\JHA;
use App\Department;
use Tests\TestCase;

class JHATest extends TestCase
{
  /**
   * @test
   *
   */
  public function a_jha_has_a_department() 
  {
    $department = factory(Department::class)->create([
      'name' => 'shipping'
    ]);

    $jha = factory(JHA::class)->create([
      'department_id' => $department->id
    ]);

    $this->assertCount(1, Department::getDept('shipping')->jhas);

    $this->assertEquals($department->id, $jha->department->id);
  }
}
