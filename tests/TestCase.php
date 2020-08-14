<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication, RefreshDatabase, WithFaker;

  public $apiEndpoint = '/api';

  public function setUp() : void
  {
    parent::setUp();
  } 

  public function apiGet($route = '') 
  {
    return $this->get($this->apiEndpoint($route));
  } 

  public function apiPost($route = '', $payload = []) 
  {
    return $this->post($this->apiEndpoint($route), $payload);
  } 

  public function apiPut($route = '', $payload = []) 
  {
    return $this->put($this->apiEndpoint($route), $payload);
  } 

  public function apiDelete($route = '') 
  {
    return $this->delete($this->apiEndpoint($route));
  } 

  public function apiEndpoint($route) 
  {
    if(substr($route, 0, 1) != '/')
      $route = '/' . $route;

    return $this->apiEndpoint . $route;
  } 

  public function as(User $user) 
  {
    Passport::actingAs($user);

    return $this;
  } 

  public function createUser($config = []) 
  {
    return factory(User::class)->create($config);
  } 

  public function createEmployee($config = []) 
  {
    return factory(User::class)->states('employee')->create($config);
  } 

  public function createSupervisor($config = []) 
  {
    return factory(User::class)->states('supervisor')->create($config);
  } 
}
