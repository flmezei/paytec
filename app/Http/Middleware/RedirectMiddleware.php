<?php

use Closure;
use App\Models\MyRedirect;
use App\Models\MyRedirectLog;

class RedirectMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $code = $request->route('redirect');
            $redirect = MyRedirect::where('code', $code)->firstOrFail();

            MyRedirectLog::create([
                'redirect_id' => $redirect->id,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
                'query_params' => $request->query(),
            ]);

            return redirect()->away($redirect->url);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Redirect not found.');
        } catch (\Exception $e) {
            abort(500, 'Internal Server Error.');
        }
    }
}
