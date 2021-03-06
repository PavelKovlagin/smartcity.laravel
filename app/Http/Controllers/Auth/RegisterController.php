<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'subname' => $data['subname'],
            'date' => $data['date'],
            'email' => $data['email'],
            'role_id' => "3",
            'password' => Hash::make($data['password']),
        ]);
    }
    //api регистраиии пользователя
    protected function apiRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
         if ($validator->fails()) {
             return $this->sendError($validator->errors(), 'Validation error', 418);
         }
         if(User::where('email', '=', $request->email)->exists())
         {
             $error = ["error" => "error exist"];
             return $this->sendError($error, 'Email exist', 418);
         }

         $input = $request->all();
         $input['password'] = bcrypt($input['password']);
         $input['role_id'] = 3;
         $user = User::create($input);
         $success['token'] = $user->createToken('MyApp')->accessToken;
         $success['name'] = $user->name;
        
         return $this->sendResponse($success, 'User register successfully.');
    }
}
