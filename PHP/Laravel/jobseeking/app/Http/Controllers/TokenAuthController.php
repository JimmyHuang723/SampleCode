<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
 
use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
 
 
class TokenAuthController extends Controller
{
    // It takes the email and password from the request and try to generate a token for the given user credential.
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
 
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
 
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }
 
    // parse the token in the request and if the token is valid and the user is present it return the user itself
    public function getAuthenticatedUser()
    {
        try {
 
            if (! $user = JWTAuth::parseToken()->authenticate()) {
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
 
    // parse the request and create a new User in the database hashing the password
    public function register(Request $request){
 
        $newuser= $request->all();

        $password=Hash::make($request->input('password'));
 
        $newuser['password'] = $password;
 
        return User::create($newuser);
    }

    public function search(Request $request){
 
        $search= $request->all();
        // ... 
        
        return response()->json(compact('search'));
    }

}
 