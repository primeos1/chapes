<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Email2FA {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (get_option('enable_2fa', 'no') == 'no') {
            return $next($request);
        }

        $user = auth()->user();

        if (auth()->check() && $user->two_factor_code) {
            if ($user->two_factor_expires_at->lt(now())) {
                $user->resetTwoFactorCode();
                auth()->logout();

                return redirect()->route('login')
                    ->withErrors(['two_factor_expired' =>
                        _lang('The two factor code has expired. Please login again.')]);
            }

            if (!$request->is('verify*')) {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
