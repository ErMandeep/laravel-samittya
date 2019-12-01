<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


Class OfflineusersController extends Controller
{
	public function index()
    {

        if (request('show_deleted') == 1) {

            $users = User::role('teacher')->onlyTrashed()->get();
        } else {
            $users = User::role('teacher')->get();
        }

        return view('backend.offline.index', compact('users'));
    }
}