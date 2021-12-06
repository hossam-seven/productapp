<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'  => 'required',
            'email' => 'required | email',
            'password' => 'required',
            'c_password' => 'required | same:password'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please Validate Error', $validator->errors());
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('hossam')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User Registered Successfully');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('hossam')->accessToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User Login Successfully');
        }
        else {
            return $this->sendError('Please Check Your Auth', ['error' => 'Unauthorized']);
        }
    }
}
