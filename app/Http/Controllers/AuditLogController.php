<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Organization;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $organization = Organization::first();
        $logs = AuditLog::where('organization_id', $organization->id ?? '')
            ->with('user')
            ->latest('created_at')
            ->paginate(20);

        return view('dashboard.audit-logs', compact('logs'));
    }
}
