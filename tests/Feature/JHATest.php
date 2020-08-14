<?php

namespace Tests\Feature;

use App\JHA;
use App\Department;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class JHATest extends TestCase
{
  /**
   * @test
   *
   */
  public function a_user_can_get_all_jhas() 
  {
    $jhas = factory(JHA::class, 5)->create();

    $user = $this->createUser();

    $response = $this->as($user)
                     ->apiGet('jhas');

    $json = $response->json();

    $response->assertStatus(200);

    $this->assertCount(5, $json['data']);
  }

  /**
   * @test
   *
   */
  public function a_user_can_get_all_jhas_in_a_department() 
  {
    $user = $this->createUser();

    $department = factory(Department::class)->create();

    $jhas = factory(JHA::class, 5)->create([
      'department_id' => $department->id
    ]);

    $response = $this->as($user)
      ->apiGet('departments/' . $department->id . '/jhas');

    $response->assertStatus(200);

    $this->assertCount(5, $response->json()['data']);
  }

  /**
   * @test
   *
   */
  public function a_user_can_filter_jhas_by_department() 
  {
    $user = $this->createUser();

    [
      $department1, 
      $department2
    ] = factory(Department::class, 2)->create();

    factory(JHA::class, 2)->create([
      'department_id' => $department1->id
    ]);

    factory(JHA::class, 3)->create([
      'department_id' => $department2->id
    ]);

    $response = $this->as($user)
      ->apiGet('/jhas?department=' . $department1->id);

    $this->assertCount(2, $response->json()['data']);
  }

  /**
   * @test
   *
   */
  public function a_user_can_create_a_jha() 
  {
    $department = factory(Department::class)->create([
      'name' => 'shipping'
    ]);

    $supervisor = $this->createSupervisor([
      'department_id' => $department->id
    ]);

    $employee = $this->createEmployee([
      'department_id' => $department->id
    ]);

    $payload = [
      'department_id' => $department->id,
      'supervisor_id' => $supervisor->id,
      'task_name' => 'Using a Hand Truck',
      'ppe' => [
        'gloves',
        'boots',
        'safety glasses'
      ],
      'training' => [
        'hand truck',
        'ppe'
      ],
      'steps' => [
        [
          'task' => 'Pre-operation Saftey Check',
          'hazards' => [
            'Untrained operator'
          ],
          'controls' => [
            'training on hand truck design, controls, and instrumentation',
            'training on hand truck stability and the proper way to transport, load, and stack on the hand truck'
          ]
        ],
        [
          'task' => 'Assembling a load',
          'hazards' => [
            'Rolling the wheels off the edge of ramps and loading docks'
          ],
          'controls' => [
            'Stay well back form the edge',
            'Never turn around on a slope',
            'When going down a ramp, keep the truck ahead of you',
            'When going up a ramp, pull the truck behind you',
            'Make sure the chisel of the truck is all the way under the load'
          ]
        ],
        [
          'task' => 'Operating the Two-wheel Hand truck',
          'hazards' => [
            'Slip/trip/fall'
          ],
          'controls' => [
            'Slow down for turns',
            'Make sure that you have enough overhead clearence'
          ]
        ],
        [
          'task' => 'Transporting the load',
          'hazards' => [
            'Pinching hands between the truck and other objects'
          ],
          'controls' => [
            'Be alert',
            'Wear gloves to protect your hands',
            'Strap bulky or dangerous cargo to the truck\'s frame',
            'When moving a stack of objects, put the heavier ones on the bottom'
          ]
        ],
        [
          'task' => 'Storing the hand truck',
          'hazards' => [
            'Trip hazard'
          ],
          'controls' => [
            'Store in a safe out of the way area'
          ]
        ]
      ]
    ];

    $response = $this->as($employee)
      ->apiPost('jhas', $payload);

    $data = $response->json()['data'];

    $this->assertEquals($employee->id, $data['preparer']['id']);
  }

  /**
   * @test
   *
   */
  public function a_supervisor_can_approve_an_application() 
  {
    $super = $this->createSupervisor();

    $jha = factory(JHA::class)->create();

    $response = $this->as($super)
      ->apiPost('/jhas/' . $jha->id . '/approve', []);

    $data = $response->json()['data'];

    $this->assertEquals($super->id, $data['approved_by']['id']);
    $this->assertNotNull($data['approved_at']);
  }

  /**
   * @test
   *
   */
  public function only_supervisors_can_approve_an_application() 
  {
    $employee = $this->createEmployee();

    $jha = factory(JHA::class)->create();

    $response = $this->as($employee)
      ->apiPost('/jhas/' . $jha->id . '/approve', []);

    $response->assertStatus(403);

    $jha->refresh();

    $this->assertNull($jha->approved_at);
    $this->assertNull($jha->approver);
  }

  /**
   * @test
   *
   */
  public function a_supervisor_can_review_an_application() 
  {
    $super = $this->createSupervisor();

    $jha = factory(JHA::class)->create();

    $response = $this->as($super)
      ->apiPost('/jhas/' . $jha->id . '/review', []);

    $data = $response->json()['data'];

    $this->assertEquals($super->id, $data['reviewed_by']['id']);
    $this->assertNotNull($data['reviewed_at']);
  }

  /**
   * @test
   *
   */
  public function only_supervisors_can_review_an_application() 
  {
    $employee = $this->createEmployee();

    $jha = factory(JHA::class)->create();

    $response = $this->as($employee)
      ->apiPost('/jhas/' . $jha->id . '/review', []);

    $response->assertStatus(403);

    $jha->refresh();

    $this->assertNull($jha->reviewed_at);
    $this->assertNull($jha->reviewer);
  }

  /**
   * @test
   *
   */
  public function a_user_can_update_a_jha() 
  {
    $employee = $this->createEmployee();

    $jha = factory(JHA::class)->create([
      'prepared_by_id' => $employee->id
    ]);

    $steps = $jha->steps;

    $steps[] = [
      'task' => 'new task',
      'hazards' => [
        'make sure theres some hazards'
      ],
      'controls' => [
        'make sure they are controlled'
      ]
    ];

    $payload = [
      'task_name' => 'Use Hand Truck',
      'ppe' => [],
      'steps' => $steps
    ];

    $response = $this->as($employee)
      ->apiPut('/jhas/'. $jha->id, $payload);

    $data = $response->json()['data'];

    $this->assertEmpty($data['ppe']);
    $this->assertCount(count($steps), $data['steps']);
  }

  /**
   * @test
   *
   */
  public function an_employee_can_only_update_jhas_they_prepared() 
  {
    $jha = factory(JHA::class)->create();

    $employee = $this->createEmployee();

    $new_task_name = 'this will not be renamed';

    $payload = [
      'task_name' => $new_task_name
    ];

    $response = $this->as($employee)
                     ->apiPut('/jhas/' . $jha->id, $payload);

    $response->assertStatus(403);

    $this->assertNotEquals($new_task_name, $jha->refresh()->task_name);
  }

  /**
   * @test
   *
   */
  public function a_supervisor_can_update_any_jha() 
  {
    $jha = factory(JHA::class)->create();

    $supervisor = $this->createSupervisor();

    $new_department = factory(Department::class)->create();

    $payload = [
      'department_id' => $new_department->id
    ];

    $response = $this->as($supervisor)
                     ->apiPut('/jhas/' . $jha->id, $payload);

    $this->assertEquals($new_department->id, $response->json()['data']['department']['id']);
  }

  /**
   * @test
   *
   */
  public function an_employee_can_delete_jhas_they_prepared() 
  {
    $employee = $this->createEmployee();

    $jha = factory(JHA::class)->create([
      'prepared_by_id' => $employee->id
    ]);

    $jha_id = $jha->id;

    $response = $this->as($employee)
      ->apiDelete('/jhas/' . $jha->id);

    $response->assertStatus(204);

    $this->assertEmpty(JHA::find($jha_id));
  }

  /**
   * @test
   *
   */
  public function an_employee_can_only_delete_jhas_they_prepared() 
  {
    $employee = $this->createEmployee();

    $jha = factory(JHA::class)->create();

    $jha_id = $jha->id;

    $response = $this->as($employee)
      ->apiDelete('/jhas/' . $jha->id);

    $response->assertStatus(403);

    $this->assertNotEmpty(JHA::find($jha_id));
  }

  /**
   * @test
   *
   */
  public function a_supervisor_can_delete_any_jhas() 
  {
    $super = $this->createSupervisor();

    $jha = factory(JHA::class)->create();

    $jha_id = $jha->id;

    $response = $this->as($super)
      ->apiDelete('/jhas/' . $jha->id);

    $response->assertStatus(204);

    $this->assertEmpty(JHA::find($jha_id));
  }
}
