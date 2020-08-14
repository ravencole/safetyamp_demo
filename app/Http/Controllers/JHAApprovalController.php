<?php

namespace App\Http\Controllers;

use App\JHA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\JHA as JHAResource;

class JHAApprovalController extends Controller
{
  public function store(Request $request, JHA $jha) 
  {
    if(! Auth::user()->isSupervisor())
      return response()->json([], 403);

    $jha->approved_at = now();
    $jha->approved_by_id = Auth::id();

    $jha->save();

    return new JHAResource($jha);
  } 
}
