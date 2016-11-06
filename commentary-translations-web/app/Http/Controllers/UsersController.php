<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Contracts\Repositories\RoleRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Users controller
 *
 * @package     App\Http\Controllers
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/skd
 * @since       Class available since Release 0.1.0
 */
class UsersController extends Controller
{

    /**
     * User repository instance
     *
     * @access protected
     * @type   UserRepositoryInterface
     */
    protected $user;

    /**
     * Role repository instance
     *
     * @access protected
     * @type   RoleRepositoryInterface
     */
    protected $role;

    /**
     * Driver repository instance
     *
     * @access protected
     * @type   DriverRepositoryInterface
     */
    protected $driver;

    /**
     * Customer repository instance
     *
     * @access protected
     * @type   CustomerRepositoryInterface
     */
    protected $customer;

    /**
     * Navigation highlight
     *
     * @access protected
     * @type   string
     */
    protected $navigation = "User";


    /**
     * Create a controller instance
     *
     * @param  UserRepositoryInterface     $user
     * @param  RoleRepositoryInterface     $role
     * @param  DriverRepositoryInterface   $driver
     * @param  CustomerRepositoryInterface $customer
     *
     * @access public
     */
    public function __construct(
        UserRepositoryInterface $user,
        RoleRepositoryInterface $role,
        DriverRepositoryInterface $driver,
        CustomerRepositoryInterface $customer
    ) {
        $this->user     = $user;
        $this->role     = $role;
        $this->driver   = $driver;
        $this->customer = $customer;
    }


    /**
     * List out all the users
     *
     * @access public
     * @return view
     */
    public function index()
    {
        $navigation = $this->navigation;

        return view('users.index', compact('navigation'));
    }


    /**
     * Display a specific user
     *
     * @param  integer $id
     *
     * @access public
     * @return view
     */
    public function show($id)
    {
        $user       = $this->user->find($id);
        $navigation = $this->navigation;

        return view('users.show', compact('user', 'navigation'));
    }


    /**
     * Create a new user
     *
     * @access public
     * @return view
     */
    public function create()
    {
        $roles      = $this->role->whereIn([['id', ['1', '2', '5']]])->lists('description', 'id');
        $navigation = $this->navigation;

        return view('users.create', compact('roles', 'navigation'));
    }


    /**
     * Store the new user
     *
     * @param  CreateUserRequest $request
     *
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $driver_id   = null;
        $customer_id = null;

        // Get the role details to check if driver code or customer code is required
        $role = $this->role->find($request['role_id']);

        // Set the parameters
        $parameters = $request->only('first_name', 'last_name', 'username', 'email');

        $parameters["role_id"]  = $role->id;
        $parameters["password"] = bcrypt($request['password']);

        if ($role->name == 'Driver') {
            $this->validate($request, ['driver_code' => 'required']);
            $driver = $this->driver->where('code', '=', $request['driver_code'])->first();

            $parameters["driver_id"] = $driver->id;

            // Update the user details
            $driver_attributes = $request->only('first_name', 'last_name', 'email');
            $this->driver->update($driver->id, $driver_attributes);
        } elseif ($role->name == 'Customer') {
            $this->validate($request, ['customer_code' => 'required']);
            $customer = $this->customer->where('code', '=', $request['customer_code'])->first();

            $parameters["customer_id"] = $customer->id;
        }

        $this->user->create($parameters);

        return redirect()->route('users.index')->with('message', 'Successfully created a user.');
    }


    /**
     * Edit an existing driver
     *
     * @param  integer $id
     *
     * @access public
     * @return view
     */
    public function edit($id)
    {
        $user       = $this->user->find($id);
        $roles      = $this->role->whereIn([['id', ['1', '2', '5']]])->lists('description', 'id');
        $navigation = $this->navigation;

        return view('users.edit', compact('user', 'roles', 'navigation'));
    }


    /**
     * Update the existing user information
     *
     * @param  UpdateUserRequest $request
     * @param  integer           $id
     *
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $driver_id   = null;
        $customer_id = null;

        // Validate if the unique username and email except current updated one
        $this->validate($request, [
            'username' => 'required|unique:users,username,' . $id,
            'email'    => 'unique:users,email,' . $id
        ]);

        // Get the role details to check if driver code or customer code is required
        $role = $this->role->find($request['role_id']);

        // Set the parameters
        $parameters = $request->only('first_name', 'last_name', 'username', 'email');

        $parameters["role_id"] = $role->id;

        if ($role->name == 'Driver') {
            $this->validate($request, ['driver_code' => 'required']);
            $driver = $this->driver->where('code', '=', $request['driver_code'])->first();

            $parameters["driver_id"] = $driver->id;

            // Update the user details
            $driver_attributes = $request->only('first_name', 'last_name', 'email');
            $this->driver->update($driver->id, $driver_attributes);
        } elseif ($role->name == 'Customer') {
            $this->validate($request, ['customer_code' => 'required']);
            $customer = $this->customer->where('code', '=', $request['customer_code'])->first();

            $parameters["customer_id"] = $customer->id;
        }

        $this->user->update($id, $parameters);

        return redirect()->route('users.index')->with('message', 'Successfully updated the user.');
    }


    /**
     * Edit the user password
     *
     * @param  integer $id
     *
     * @access public
     * @return view
     */
    public function editPassword($id)
    {
        $user       = $this->user->find($id);
        $navigation = $this->navigation;

        return view('users.edit-password', compact('user', 'navigation'));
    }


    /**
     * Update the existing user information
     *
     * @param  Request $request
     * @param  integer $id
     *
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        // Validate the password
        $this->validate($request, ['password' => 'required|confirmed|min:6|max:60']);

        // Update the password
        $parameters["password"] = bcrypt($request['password']);
        $this->user->update($id, $parameters);

        return redirect()->route('users.index')->with('message', 'Successfully updated the password of the user.');
    }
}
