<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|unique:users,username',
            'name' => 'required|max:255',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $message['success'] = ['Registered Account Successfully!'];
        $data = User::create($input);
        return ApiFormatter::format(200, true, $message, [new UserResource($data)]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = User::where('username', $request->username)->first();
            $success['username'] = $user->username;
            $success['authentication'] = 'Bearer';
            $success['token'] =  $user->createToken('api_token')->plainTextToken;
            return ApiFormatter::format(200, true, ['Login Success'], [$success]);
        } else {
            return ApiFormatter::format(404, false, ['Login failed']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiFormatter::format(200, true, ['Logout account successfully']);
    }

    public function detail()
    {
        $user = Auth::user();
        $data['id'] = $user->id;
        $data['username'] = $user->username;
        $data['name'] = $user->name;
        return ApiFormatter::format(200, true, 'User Information', [$data]);
    }
}
