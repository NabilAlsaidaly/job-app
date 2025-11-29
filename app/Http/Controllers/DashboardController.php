<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::query();

        // فلترة حسب النوع إذا وُجدت
        if ($request->filled('filter')) {
            $query->where('type', $request->filter);
        }

        // البحث إذا وُجد
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('jobs'));
    }
}
