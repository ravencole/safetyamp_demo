<?php

namespace App\Http\Controllers;

use App\JHA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\JHA as JHAResource;

class MyJHAsController extends Controller
{
  private $orderBys = [
    'created_at',
    'updated_at',
    'reviewd_at',
    'approved_at'
  ];

  private $statuses = [
    'reviewed',
    'approved',
    'completed',
    'created'
  ];

  public function index(Request $request) 
  {
    $user = Auth::user();

    $jhas = JHA::with([]);

    if($user->isSupervisor())
      $jhas->where('supervisor_id', $user->id)
        ->orWhere('reviewed_by_id', $user->id)
        ->orWhere('approved_by_id', $user->id);
    else
      $jhas->where('prepared_by_id', $user->id);

    if(
      $request->has('orderBy') && 
      in_array($request->query('orderBy'), $this->orderBys)
    ) 
      $jhas->orderBy(
        $request->query('orderBy'),
        $request->query('sort') === 'desc' ? 'desc' : 'asc'
      );

    if(
      $request->has('status') && 
      in_array($request->query('status'), $this->statuses)
    ) {
      $status = $request->query('status');

      $jhas->$status();
    }

    return JHAResource::collection($jhas->get());
  } 
}
