<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\JHA;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreJHA;
use App\Http\Requests\UpdateJHA;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\JHA as JHAResource;

class JHAsController extends Controller
{
  // Map items from the url query string to 
  // where clauses on the JHA model
  // key = query string parameter
  // val = model property
  protected $indexQueryStringMapping = [
    // Query key    // Model property
    'department' => 'department_id',
    'supervisor' => 'supervisor_id',
    'preparedBy' => 'prepared_by_id',
  ];

  public function __construct() 
  {
    $this->indexQueryStringMapping = collect($this->indexQueryStringMapping);
  } 

  public function index(Request $request) 
  {
    $jhas = JHA::with([]);

    $this->addQueryStringClauses(
      $this->indexQueryStringMapping, 
      $request, 
      $jhas
    );

    return JHAResource::collection($jhas->get());
  } 

  public function store(StoreJHA $request) 
  {
    $data = $request->validated();

    $jha = JHA::create([
      'department_id' => $data['department_id'],
      'supervisor_id' => $data['supervisor_id'],
      'prepared_by_id' => Auth::id(),
      'task_name' => $data['task_name'],
      'ppe' => $data['ppe'],
      'training' => $data['training'],
      'steps' => $data['steps']
    ]);

    return new JHAResource($jha);
  } 

  public function update(UpdateJHA $request, JHA $jha) 
  {
    $data = $request->validated();

    $remove_nulls = [
      'task_name',
      'department_id',
      'supervisor_id'
    ];

    foreach($remove_nulls as $key) {
      if(
        array_key_exists($key, $data) &&
        (is_null($data[$key]) || empty($data[$key]))
      )
        unset($data[$key]);
    }

    $jha->update($data);

    return new JHAResource($jha);
  } 

  public function destroy(Request $request, JHA $jha) 
  {
    $user = Auth::user();

    if(! $user->isSupervisor() && $jha->preparer->id !== $user->id)
      return response()->json([], 403);

    $jha->delete();

    return response()->json([], 204);
  } 
}
