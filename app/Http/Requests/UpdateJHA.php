<?php

namespace App\Http\Requests;

use App\JHA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateJHA extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    $user = Auth::user();

    $jha = $this->route('jha');

    if(! $jha)
      return false;
    
    return $user->isSupervisor() || $jha->preparer->id === $user->id;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    if(Auth::user()->isSupervisor())
      return $this->supervisorRules();
    return $this->employeeRules();
  }

  public function employeeRules() 
  {
    $rules = [];
    
    return array_merge($this->commonRules(), $rules);
  } 

  public function supervisorRules() 
  {
    $rules = [
      'supervisor_id' => [
        'nullable',
        'numeric',
        function($key, $val) {
          $user = User::find($val);

          $error_messages = ['supervisor_id' => []];

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
      ]
    ];

    return array_merge($this->commonRules(), $rules);
  } 

  public function commonRules() 
  {
    return [
      'department_id' => [
        'nullable',
        'numeric',
        'exists:departments,id'
      ],
      'task_name' => [
        'nullable',
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
        'nullable',
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
