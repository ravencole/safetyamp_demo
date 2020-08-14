<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function addQueryStringClauses(Collection $mapping, Request $request, $model) 
  {
    $mapping->each(function($item, $key) use ($model, $request) {
      if($request->has($key)) 
        $model->where($item, $request->query($key));
    });
  } 
}
