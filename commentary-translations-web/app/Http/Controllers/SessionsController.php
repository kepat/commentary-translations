<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Guard as Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SessionsController extends Controller
{
    /**
     * Auth instance.
     *
     * @access protected
     * @type   Guard
     */
    protected $auth;


    /**
     * Create a controller instance.
     *
     * @param Auth $auth
     *
     * @access public
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Login page
     *
     * @access public
     * @return view
     */
    public function create()
    {
        return view('sessions.create');
    }


    /**
     * Verify the users credential
     *
     * @param  Request $request
     *
     * @access public
     * @return redirect
     */
    public function store(Request $request)
    {
        // Validates the username or email
        $this->validate($request, ['email' => 'required']);

        // Store the required credential details
        $username = $request->get('email');
        $password = $request->get('password');

        // For email credential
        $email_credentials = ['email' => $username, 'password' => $password];

        // For username credential
        $username_credentials = ['username' => $username, 'password' => $password];

        // Validate the user credential
        if (! $this->auth->attempt($email_credentials) && ! $this->auth->attempt($username_credentials)) {
            return redirect()->back()->withErrors(['Incorrect username or password.']);
        }

        return redirect()->intended('/');
    }


    /**
     * User logout
     *
     * @access public
     * @return redirect
     */
    public function destroy()
    {
        $this->auth->logout();

        return redirect()->route('sessions.create')->with('message', 'Logout Successful');
    }
}
