<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q'));
        $action = trim((string) $request->query('action'));
        $module = trim((string) $request->query('module'));
        $date   = trim((string) $request->query('date'));

        $query = ActivityLog::with('user')->orderBy('id', 'desc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%")
                    ->orWhere('action', 'like', "%{$q}%")
                    ->orWhere('module', 'like', "%{$q}%");
            });
        }

        if ($action !== '') {
            $query->where('action', $action);
        }

        if ($module !== '') {
            $query->where('module', $module);
        }

        if ($date !== '') {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->paginate(15)->withQueryString();

        // Statistik Keseluruhan
        $stats = [
            'total'      => ActivityLog::count(),
            'today'      => ActivityLog::whereDate('created_at', now()->toDateString())->count(),
            'logins'     => ActivityLog::where('action', 'LOGIN')->count(),
            'operations' => ActivityLog::whereIn('action', ['CREATE', 'UPDATE', 'DELETE', 'CALCULATE', 'IMPORT'])->count(),
        ];

        // Daftar aksi dan modul unik untuk filter
        $availableActions = ActivityLog::select('action')->distinct()->pluck('action');
        $availableModules = ActivityLog::select('module')->distinct()->pluck('module');

        return view('activity_logs.index', compact(
            'logs',
            'stats',
            'q',
            'action',
            'module',
            'date',
            'availableActions',
            'availableModules'
        ));
    }

    /**
     * Hapus satu log aktivitas berdasarkan ID
     */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $desc = $log->description;
        $log->delete();

        ActivityLog::record('DELETE', 'Log Aktivitas', "Menghapus 1 log aktivitas: \"{$desc}\"");

        return redirect()->route('activity-logs.index')->with('ok', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Hapus banyak log aktivitas sekaligus (bulk delete)
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('activity-logs.index')->with('warn', 'Tidak ada log yang dipilih untuk dihapus.');
        }

        $count = ActivityLog::whereIn('id', $ids)->count();
        ActivityLog::whereIn('id', $ids)->delete();

        ActivityLog::record('DELETE', 'Log Aktivitas', "Menghapus {$count} log aktivitas secara massal (bulk delete).");

        return redirect()->route('activity-logs.index')->with('ok', "{$count} log aktivitas berhasil dihapus.");
    }
}
