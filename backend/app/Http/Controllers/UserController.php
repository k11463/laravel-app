<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Models\Token;
use App\Models\EmailVerify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function findUsers() {
        $users = User::all();
        return $users;
    }

    public function findUser(Request $req) {
        $findUser = Token::where('token', $req->token)->first();        
        if ($findUser){
            $user = $findUser->user;
            $checkImageIsExist = Storage::files($user->avatar);
            if ($checkImageIsExist != null){
                $array_path = array('avatar' => 'Avatar.jpg');
                User::where('uid', $user->uid)->update($array_path);
                return $user;
            }
            else {
                return $user;
            }
        }
        else {
            return response()->json(['msg' => '找不到使用者'], 404);
        }
    }

    public function createUser(Request $req) {
        $findUser = User::where('email', $req->email)->first();
        if(!$findUser){
            $checkVerify = $this->CheckVerify($req->email, $req->verify);
            if ($checkVerify) {
                $user = new User;
                $user->fill($req->all());
                if ($user->user_name == ""){
                    $user->user_name = "智善的奴隸";
                }
                $user->avatar = 'Avatar.jpg';
                $user->rank = "青銅";
                $user->money = 0;
                $user->password = Hash::make($req->password, [
                    'rounds' => 12
                ]);
                $user->save();
                return response()->json(['msg' => '註冊成功'], 200);
            }
            else {
                return response()->json(['msg' => '驗證碼錯誤，請重新發送驗證碼'], 402);
            }
        }
        else {
            return response()->json(['msg' => '此Email已經註冊'], 403);
            
        }        
    }

    public function login(Request $req) {
        $findUser = User::where('email', $req->email)->first();
        if($findUser) {
            $findToken = Token::where('for', 'login')->where('user_id', $findUser->uid)->first();
            if ($findToken){
                Token::where('for', 'login')->where('user_id', $findUser->uid)->delete();
            }

            if(Hash::check($req->password, $findUser->password)) {
                $tokenValue = $this->getJWTToken($findUser);
                $token = new Token;
                $token->token = $tokenValue;
                $token->user_id = $findUser->uid;
                $token->for = "login";
                $token->save();

                $res = [
                    'userName' => $findUser->user_name,
                    'token' => $token->token,
                    'email' => $findUser->email,
                    'rank' => $findUser->rank,
                    'money' => $findUser->money,
                    'avatar' => $findUser->avatar,
                    'phone' => $findUser->phone
                ];
                return $res;
            }
            else {
                return response()->json(['msg' => '帳號或密碼錯誤'], 422);
            }
        }
        else {
            return response()->json(['msg' => '帳號或密碼錯誤'], 422);
        }
    }

    // create jwt token
    public function getJWTToken($user)
    {
        $time = time();
        $payload = [
            'iat' => $time,
            'nbf' => $time,
            'exp' => $time+7200,
            'data' => [
                'email' => $user->email,
                'password' => $user->password
            ]
        ];
        $key =  env('JWT_SECRET');
        $alg = 'HS256';
        $token = JWT::encode($payload,$key,$alg);
        return $token;
    }

    public function logout(Request $req)
    {
        Token::where('token', $req)->delete();
        return response()->json(['msg' => '登出成功'], 200);
    }

    public function updateUser(Request $req) {
        $findUser = Token::where('token', $req->token)->first();
        if ($findUser){
            $user = $findUser->user;
            if ($req->type == 'name'){
                $isExist = User::where('user_name', $req->user_name)->first();
                if (!$isExist){
                    $user_name = array('user_name' => $req->user_name);
                    User::where('uid', $user->uid)->update($user_name);
                    return response()->json(['msg' => '已更新會員名稱'], 200);
                }
                else {
                    return response()->json(['msg' => '會員名稱已存在'], 402);
                }
            }
            else if ($req->type == 'email'){
                $checkVerify = $this->CheckVerify($req->email, $req->verify);
                if ($checkVerify){
                    $email = array('email' => $req->email);
                    User::where('uid', $user->uid)->update($email);
                    return response()->json(['msg' => '已更新Email'], 200);
                }
                else {
                    return response()->json(['msg' => '驗證失敗，請檢查驗證碼或重新發送驗證信'], 402);
                }
            }
            else if ($req->type == 'img'){
                $path = $req->avatar->store($req->storePath);
                $array_path = array('avatar' => $path);
                User::where('uid', $user->uid)->update($array_path);         
                return response()->json(['msg' => '變更頭像成功', 'path' => $path], 200);
            }
        }
        else {
            return response()->json(['msg' => '找不到使用者'], 404);
        }
    }

    public function CheckVerify($email, $code) {
        $findVerify = EmailVerify::where('email', $email)->first();
        if ($findVerify){
            if ($findVerify->verify_code == $code){
                EmailVerify::where('email', $req->email)->delete();
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}
