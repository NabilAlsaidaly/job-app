<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
class JobApplicationsController extends Controller
{
    public function index()
    {
        $jobApplications = JobApplication::where('userId', Auth::user()->id)->latest()->paginate(10);
        return view('job-applications.index', compact('jobApplications'));
    }
}
