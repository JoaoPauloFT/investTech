<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilterController extends Controller
{
    //
    public static function index() {
        // $roles = Role::all();

        return view('indicator.filter.index');
    }
}
