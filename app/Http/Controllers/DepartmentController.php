<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\Department as DepartmentResource;

class DepartmentController extends Controller
{
  public function index() 
  {
    return DepartmentResource::collection(Department::all());
  } 

  public function show(Request $request, Department $department) 
  {
    return (new DepartmentResource($department));
  } 
}
