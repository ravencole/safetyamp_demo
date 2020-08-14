<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JHA extends Model
{
  protected $guarded = [];

  protected $table = 'jhas';

  protected $casts = [
    'ppe' => 'array',
    'training' => 'array',
    'steps' => 'array',
  ];

  public function department() 
  {
    return $this->belongsTo(Department::class);
  } 

  public function supervisor() 
  {
    return $this->belongsTo(User::class);
  } 

  public function preparer() 
  {
    return $this->belongsTo(User::class, 'prepared_by_id');
  } 

  public function reviewer() 
  {
    return $this->belongsTo(User::class, 'reviewed_by_id');
  } 

  public function approver() 
  {
    return $this->belongsTo(User::class, 'approved_by_id');
  } 

  public function emptyAttributeToArray($value) 
  {
    if(is_null($value))
      return [];

    return json_decode($value, true);
  } 

  public function getPpeAttribute($value) 
  {
    return $this->emptyAttributeToArray($value);
  } 

  public function getTrainingAttribute($value) 
  {
    return $this->emptyAttributeToArray($value);
  } 

  public function getStepsAttribute($value) 
  {
    return $this->emptyAttributeToArray($value);
  } 

  public function scopeReviewed($query) 
  {
    return $query->whereNotNull('reviewed_at');
  } 

  public function scopeApproved($query) 
  {
    return $query->whereNotNull('approved_at');
  } 

  public function scopeCompleted($query) 
  {
    return $query->whereNotNull('approved_at')
      ->whereNotNull('reviewed_at');
  } 

  public function scopeCreated($query) 
  {
    return $query->whereNull('approved_at')
      ->whereNull('reviewed_at');
  } 
}
