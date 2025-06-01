<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    public function CustomerPage(){
        return view('admin.customer.admin_customer');
    }
}
