<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JHA extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $data = [
      'id' => $this->id,
      'department' => [
        'id' => $this->department->id,
        'name' => ucwords($this->department->name)
      ],
      'supervisor' => [
        'id' => $this->supervisor->id,
        'name' => $this->supervisor->name
      ],
      'preparer' => [
        'id' => $this->preparer->id,
        'name' => $this->preparer->name
      ],
      'task_name' => $this->task_name,
      'ppe' => $this->ppe,
      'training' => $this->training,
      'steps' => $this->steps,
      'approved_at' => $this->approved_at,
      'reviewed_at' => $this->reviewed_at,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'approved_by' => [
        'id' => null,
        'name' => null
      ],
      'reviewed_by' => [
        'id' => null,
        'name' => null
      ]
    ];

    if($this->approver)
      $data['approved_by'] = [
        'id' => $this->approver->id,
        'name' => $this->approver->name
      ];

    if($this->reviewer)
      $data['reviewed_by'] = [
        'id' => $this->reviewer->id,
        'name' => $this->reviewer->name
      ];

    return $data;
  }
}
