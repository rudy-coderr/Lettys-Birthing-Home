<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{

public function dashboards()
    {
        return view('admin.dashboard'); 
    }

public function appointments()
    {
        return view('admin.appointment'); 
    }

public function patients()
    {
        return view('admin.patient'); 
    }

public function staffs()
    {
        return view('admin.staff'); 
    }

public function medications()
    {
        return view('admin.medication'); 
    }

public function reports()
    {
        return view('admin.report'); 
    }

public function settings()
    {
        return view('admin.setting'); 
    }
    
}
