<?php
namespace App\Http\Middleware;

use App\Session;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class SessionAuthentication
{

    /**
     * The Guard implementation
     *
     * @var Guard
     */
    protected $auth;


    /**
     * Session repository instance
     *
     * @access protected
     * @type   SessionRepositoryInterface
     */
    protected $session;


    /**
     * Create a new filter instance
     *
     * @param  Guard                      $auth
     * @param  SessionRepositoryInterface $session
     *
     * @access public
     */
    public function __construct(Guard $auth, Session $session)
    {
        $this->auth    = $auth;
        $this->session = $session;
    }


    /**
     * Handle an incoming request
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @access public
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // // Get the token
        // $token = $request->get('_token');

        // // Check and validate the sessions
        // $session = $this->session->where('token', '=', $token);

        // if ($session->count() == 0) {
        //     return ['status' => 'Failed', 'data' => [], 'message' => 'Token is not existing.'];
        // }

        // if (date_diff(create_date($session->expires_at),date("Y-m-d H:i:s")) >= 0) {
        //     return ['status' => 'Failed', 'data' => [], 'message' => 'Token is already expired.'];
        // }

        return $next($request);
    }
}
