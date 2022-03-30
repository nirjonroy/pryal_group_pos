<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function types()
    {
        return view('backend.admin.client.types');
    }
}
