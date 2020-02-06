<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\UserRole;

class CheckAdmin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $role = Auth::user();

        $user_role = UserRole::find($role->user_role_id);
        
        if ($user_role->role_name === "superadmin") {
            if ($request->route()->getPrefix() == "my-account") {
                return redirect(route("admin.dashboard"));
            }
        }
        
        if ($user_role->role_name === "customer") {
            $admin_url = config("app.admin_url");
            if ($request->route()->getPrefix() == "/".$admin_url) {
                return redirect(route("my-account"));
            }
        }
        
        return $next($request);
    }

}
