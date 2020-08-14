<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreJHA extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'department_id' => [
        'required',
        'numeric',
        'exists:departments,id'
      ],
      'supervisor_id' => [
        'required',
        'numeric',
        function($key, $val) {
          $user = User::find($val);

          if(is_null($user)) {
            throw ValidationException::withMessages([
              'supervisor_id' => [
                'supervisor does not exist'
              ]
            ]);
          }

          if(! $user->isSupervisor()) {
            throw ValidationException::withMessages([
              'supervisor_id' => [
                'user is not a supervisor'
              ]
            ]);
          }

          return true;
        }
      ],
      'task_name' => [
        'required',
        'string'
      ],
      'ppe' => [
        'nullable',
        'array'
      ],
      'ppe.*' => [
        'nullable',
        'string'
      ],
      'training' => [
        'required',
        'array'
      ],
      'training.*' => [
        'nullable',
        'string'
      ],
      'steps' => [
        'nullable',
        'array'
      ],
      'steps.*.task' => [
        'required',
        'string'
      ],
      'steps.*.hazards' => [
        'required',
        'array'
      ],
      'steps.*.hazards.*' => [
        'nullable',
        'string'
      ],
      'steps.*.controls' => [
        'required',
        'array'
      ],
      'steps.*.controls.*' => [
        'nullable',
        'string'
      ]
    ];
  }
}
