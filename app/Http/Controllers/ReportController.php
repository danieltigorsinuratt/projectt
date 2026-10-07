<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('nama_lengkap')->get();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $userId = $request->get('user_id');
        $action = $request->get('aksi');

        $query = ActivityLog::with('user')->latest('id');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($action) {
            $query->where('aksi', 'like', "%{$action}%");
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('reports.index', compact('logs', 'users', 'startDate', 'endDate', 'userId', 'action'));
    }
}
