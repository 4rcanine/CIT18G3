<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DropSemesterController extends Controller
{
    public function index()
    {
        return view('drop-semester.index');
    }
}
