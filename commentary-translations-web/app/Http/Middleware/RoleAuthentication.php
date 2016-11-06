<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Role Authentication Middleware
 *
 * @package     App\Http\Controllers
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.17.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.17.0
 */
class RoleAuthentication
{

    /**
     * The Guard implementation
     *
     * @var Guard
     */
    protected $auth;


    /**
     * Create a new filter instance
     *
     * @param  Guard $auth
     *
     * @access public
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Handle an incoming request
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  array                    $roles
     *
     * @access public
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Set up an array for the available roles
        $roles_data = [
            'Administrator'     => '1',
            'Driver'            => '2',
            'Customer - Web'    => '3',
            'Customer - Portal' => '4',
            'Planner'           => '5'
        ];

        // Get the role of the current user
        $user_role = $roles_data[$this->auth->user()->role->name];

        // Check if user role is allowed to access or not
        if (in_array($user_role, $roles)) {
            // Check if the page being accessed is home or / then return to the right pages per user
            if ($request->getRequestUri() == '/' || $request->getPathInfo() == '/') {
                return redirect()->route($this->getRoute($user_role));
            } else {
                return $next($request);
            }
        } else {
            return redirect()->back()->withErrors(['Page not found.']);
        }
    }


    /**
     * Gets the right route for the role
     *
     * @param  string $role
     *
     * @access public
     * @return string
     */
    public function getRoute($role)
    {
        $route = '';

        // Check the role and returns the right route
        if ($role != 0) {
            $route = 'deliveries.index';
        }

        return $route;
    }

}
