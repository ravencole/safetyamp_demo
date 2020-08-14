<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  protected $guarded = [];

  public static function getDept($name) 
  {
    return self::getDepartment($name);
  } 

  public static function getDepartment($name) 
  {
    return self::where('name', $name)->first();
  } 

  public function jhas() 
  {
    return $this->hasMany(JHA::class);
  } 

  public function users() 
  {
    return $this->hasMany(User::class);
  } 

  public function employees() 
  {
    return $this->hasMany(User::class)->where('role', 'employee');
  } 

  public function supervisors() 
  {
    return $this->hasMany(User::class)->where('role', 'supervisor');
  } 
}
