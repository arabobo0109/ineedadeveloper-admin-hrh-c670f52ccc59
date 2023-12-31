<?php
namespace Simcify\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Simcify\Auth;

class RedirectIfAuthenticated implements IMiddleware {

    /**
     * Redirect the user if they are already authenticated
     * 
     * @param   \Pecee\Http\Request $request
     * @return  \Pecee\Http]Request
     */
    public function handle(Request $request) {

        Auth::remember();

        if (Auth::check()) {
            if (Auth::user()->role=='user')
                $request->setRewriteUrl(url('Dashboard@get'));
            else
                $request->setRewriteUrl(url('Customer@get'));
        }
        return $request;
    }
}
