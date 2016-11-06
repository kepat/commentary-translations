<?php namespace App\Http\Requests;

/**
 * User creation request
 *
 * @package     App\Http\Controllers
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/skd
 * @since       Class available since Release 0.1.0
 */
class CreateUserRequest extends Request
{


    /**
     * Determine if the user is authorized to make this request
     *
     * @access public
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'username'      => 'required|unique:users',
            'email'         => 'unique:users|email',
            'password'      => 'required|confirmed|min:6|max:60',
            'role_id'       => 'required|integer|exists:roles,id',
            'driver_code'   => 'exists:drivers,code',
            'customer_code' => 'exists:customers,code'
        ];
    }
}
