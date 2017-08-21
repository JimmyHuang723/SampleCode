<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Domains\HubUser;
use App\Domains\Permission;

class OAuthController extends Controller
{

    public function login(Request $request){
        $userName = $request->input('email');
        $password = $request->input('password');

        if($userName=='' || $password=='') return redirect('/');

        if(env('USE_OAUTH')){
            $result = $this->loginUsingOAuth($userName, $password);
        } else {
            $result = $this->loginUsingLocalMySql($userName, $password);
        }
        if($result['status']==200)
        {
            return redirect('/dashboard');
        } else {
            return redirect()-> back()->withErrors(['password' => 'Access denied']);
        }
    }

    public function logout(){
        // setcookie("facility", "", time() - 3600);
        // setcookie("FacilityName", "", time() - 3600);
        Auth::logout();
        return redirect('/'); 
    }

    // this method is used only for development purpose
    private function loginUsingLocalMySql($userName, $password){
        if(Auth::attempt(['name' => $userName, 'password' => $password])){
            
            $hubUser = HubUser::where('UserName', $userName)->get()->first();
            if($hubUser == null)
                return ['status' => 404];
            else{
                $user= User::where('name', $userName)->get()->first();
                $user->SID = $hubUser->SID;
                $user->save();

                session(['username'=> $hubUser->Fullname]);
                $permissions = Permission::SetupPermission($hubUser);
                session($permissions);

                return ["status" => 200];
            }
        } else {
            return ["status" => 400];
        }
    }

    // login method for production
    private function loginUsingOAuth($userName, $password){

        $res = $this->GetAccessToken($userName, $password);

        if($res->getStatusCode()==200){
            // this is a valid user
            // get access token
            $body = $res->getBody();
            $accessToken = '';
            while (!$body->eof()) {
                $accessToken = $accessToken. $body->read(1024);
            }
            $this->getJwtToken($userName, $accessToken);

            $hubUser = HubUser::where('UserName', $userName)->get()->first();

            $user = User::where('name', $userName)->get()->first();
            if($user == null){
                // new user, let add one in
                $user = new User();
                $user->name = $userName;
                $user->email = $hubUser->SEmail;
                $user->password = bcrypt($password);
                $user->save();
            }
            Auth::login($user);

            session(['username'=> $hubUser->Fullname]);
            $permissions = Permission::SetupPermission($hubUser);
            session($permissions);

            return ["status" => 200];
        } else {
            return ["status" => $res->getStatusCode()];
        }
    }

    private function GetAccessToken($userName, $password){
        $client = new Client(); //GuzzleHttp\Client
        $res = $client->post(env('OAUTH_SERVER_URL').'/oauth/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'username' => $userName,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_secret' => env('OAUTH_CLIENT_SECRET')
            ],
            "http_errors" => false
        ]);
        
        
        return $res;
        
    }

    private function getJwtToken($userName, $accessToken){
        $at = json_decode($accessToken);
        // dd($at);
       // dd($at->access_token);
        //dd($at->expires_in);

        $key = env('OAUTH_CLIENT_SECRET');
        $token = array(
            "userId" => url(''),
            "username" => $userName,
            "firstName" => $userName,
            "lastName" => $userName,
            "username" => $userName,
            "rules" => [],
            "facilities" => [],
            "hr_facilities" => [],
            "exp" => ($at->expires_in +  time()),
            "accesstoken" => $at->access_token,
            "functionpermission" => [],
            "editpermission" => [],
            "deletepermission" => []
        );

        $jwt = JWT::encode($token, $key);
        //dd($jwt);
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        // dd($decoded);
        return $jwt;
    }

}