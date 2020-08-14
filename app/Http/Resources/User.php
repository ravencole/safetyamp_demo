<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'email' => $this->email,
      'id' => $this->id,
      'name' => $this->name,
      'role' => $this->role,
      'department' => [
        'id' => $this->department->id,
        'name' => $this->department->name
      ]
    ];
  }
}
