<?php
namespace App\Http\Controllers\Api;

use App\Session;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard as Auth;

class SessionsController extends Controller
{

    /**
     * Auth instance
     *
     * @access private
     * @type \Illuminate\Contracts\Auth\Guard
     */
    private $auth;

    /**
     * Create a Driver controller instance
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param SessionRepositoryInterface       $session
     *
     * @access public
     */
    public function __construct(Auth $auth)
    {
        $this->auth    = $auth;
        $this->session = new Session;

    }

    /**
     * Validate the login credentials and create a session
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Request
     */
    public function store(Request $request)
    {
        // Store the required credential details
        $username = $request->get('email');
        $password = $request->get('password');

        // For email credential
        $email_credentials = ['email' => $username, 'password' => $password];

        // For username credential
        $username_credentials = ['username' => $username, 'password' => $password];

        // Validate the user credential
        if (! $this->auth->attempt($email_credentials) && ! $this->auth->attempt($username_credentials)) {
            return $this->jsonResult('fail', [], 'Incorrect username and password combination given.');
        }

        $attributes = [
            'token'      => uniqid('', true),
            'user_id'    => $this->auth->user()->id,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 week')),
        ];

        // Create the session
        $session = $this->session->create($attributes);

        // Load the user details
        $session->load('user');

        return $this->jsonResult('0', $session, 'success');
    }

}