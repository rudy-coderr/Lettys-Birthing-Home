<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class staffController extends Controller
{
   public function homes()
    {
        return view('staff.home'); 
    }

    public function staffPatients()
    {
        return view('staff.staffPatient'); 
    }

     public function staffAppointments()
    {
        return view('staff.staffAppointment'); 
    }
    
     public function bills()
    {
        return view('staff.bill'); 
    }
}
