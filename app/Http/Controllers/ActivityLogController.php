<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan halaman log aktivitas.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user.role');

        // Filter berdasarkan aksi jika ada
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        // Cari berdasarkan deskripsi atau nama pelaku
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Dapatkan jenis aksi unik untuk pilihan filter
        $actions = ActivityLog::distinct()->pluck('action');

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('activity-logs.index', compact('logs', 'actions'));
    }
}
