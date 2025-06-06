<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    public function PosPage()
    {
        return view('admin.pos.pos');
    }
}
