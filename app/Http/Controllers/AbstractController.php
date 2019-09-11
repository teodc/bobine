<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseAbstractController;

abstract class AbstractController extends BaseAbstractController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
