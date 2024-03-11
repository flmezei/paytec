<?php

namespace App\Http\Controllers;

use App\Models\MyRedirect;
use Illuminate\Http\Request;
use App\Http\Requests\RedirectRequest;
use Illuminate\Support\Facades\DB;

class RedirectController extends Controller
{
    public function store(RedirectRequest $request)
    {
        $redirect = MyRedirect::create($request->validated());
        return response()->json($redirect, 201);
    }

    public function show(MyRedirect $redirect)
    {
        return response()->json($redirect, 200);
    }

    public function update(MyRedirect $redirect, RedirectRequest $request)
    {
        $redirect->update($request->validated());
        return response()->json($redirect, 200);
    }

    public function destroy(MyRedirect $redirect)
    {
        $redirect->delete();
        return response()->json(null, 204);
    }

    public function stats(MyRedirect $redirect)
    {
        $redirect->load('logs');

        $totalAccesses = $redirect->logs()->count();
        $uniqueIPs = $redirect->logs()->distinct('ip')->count();
        $topReferrers = $redirect->logs()
            ->select('referer', DB::raw('count(*) as count'))
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        $accessesLast10Days = $redirect->logs()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total'),
                DB::raw('count(distinct(ip)) as unique')
            )
            ->where('redirect_id', $redirect->id)
            ->where('created_at', '>=', now()->subDays(10))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total_accesses' => $totalAccesses,
            'unique_ips' => $uniqueIPs,
            'top_referrers' => $topReferrers,
            'accesses_last_10_days' => $accessesLast10Days,
        ]);
    }

    public function activate(MyRedirect $redirect)
    {
        $redirect->update(['active' => true]);
        return response()->json($redirect, 200);
    }

    public function deactivate(MyRedirect $redirect)
    {
        $redirect->update(['active' => false]);
        return response()->json($redirect, 200);
    }
}
