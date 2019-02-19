<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends BaseController
{
    public function login(Request $request)
    {
        //$validator = Validator::make($request->only('email', 'password'),[]
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|string|email|max:255',
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return $this->sendError('errors', $validator->errors());


        $credentials = $request->only('username', 'password');
        $user = User::where('username', $credentials['username'])->first();
        if ($user == null)
            return response()->json(['error' => 'invalid username'], 401);
        $hasher = app('hash');
        //Hash::make($request->get('password'))
        $valid = $hasher->check($credentials['password'], $user->getAuthPassword());

        try {
            // if(failed to authenticate using jwt token provided by user)
            // $jwt = JWTAuth::attempt($request->only('email', 'password')); // Tymon\JWTAuth::attempt()

            //if (!$token = JWTAuth::attempt($credentials))
            if (!$valid)
                return response()->json(['error' => 'invalid username and password'], 401);

            $token = JWTAuth::fromUser($user);

        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'could not create token'], 500);
        }


        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => array_map(function ($role) {
                    return $role['name'];
                    // other way: $user->roles()->get()->toArray()
                }, $user->roles->toArray()), // array_map takes an array, so we convert a Laravel Collection to a php array
            ],

        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|string|email||unique:users|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'required'
        ], [
            'email.required' => 'You must specify an email to register',
            'email.unique' => 'There is a user with that email already'
        ]);
        // Check if validation fails
        if ($validator->fails())
            // Return error message if failure
            return response()->json($validator->errors());

        // Register the user
        $user = User::create([
            'username' => $request->get('username'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        // generate the jwt token
        $token = JWTAuth::fromUser($user);
        return $this->sendSuccessResponse(null, "User registered successfully");
        // send the jwt token
        return Response::json(compact('token'));


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [
                    'errors' => $validator->errors()->toJson() // Todo: remove tojson?
                ]
            ]);
        }
    }

    public function getProfile()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }


}

