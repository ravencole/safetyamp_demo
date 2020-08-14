<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class APITest extends TestCase
{
  /**
   * @test
   *
   */
  public function ping_test() 
  {
    $this->apiGet()
      ->assertStatus(200);
  }

  /**
   * @test
   *
   */
  public function passport_test() 
  {
    $user = $this->createUser();

    $response = $this->as($user)
                     ->apiGet('me');

    $json = $response->json();

    $response->assertStatus(200);

    $this->assertEquals(
      $user->email, 
      $json['data']['email']
    );
  }
}
