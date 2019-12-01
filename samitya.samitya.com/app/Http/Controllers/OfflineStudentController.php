<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;


class OfflineStudentController extends Controller
{

	public function index()
	{

		// return view('offline_student.index');
		return view('backend.offline_student.dashboard');

	}

}
