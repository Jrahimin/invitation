<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Invitation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [];
        $data['totalGuest'] = Invitation::sum('people_count');
        $data['totalInvited'] = Invitation::whereNotNull('sent_at')->sum('people_count');
        $data['totalUninvited'] = Invitation::whereNull('sent_at')->sum('people_count');

        return view('home', $data);
    }
}
