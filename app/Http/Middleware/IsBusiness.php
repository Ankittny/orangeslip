<?php



namespace App\Http\Middleware;



use Closure;



class IsBusiness

{

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle($request, Closure $next)

    {

        $user = auth()->user();

        if($user->user_type == 'business'){

        }else{

            //flash()->error('Access Denied !');

            //return redirect()->back();

        }

        return $next($request);

    }

}

