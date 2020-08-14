<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\JHA as JHAResource;

class DepartmentJHAsController extends Controller
{
  public function index(Request $request, Department $department) 
  {
    return JHAResource::collection($department->jhas);
  } 
}
