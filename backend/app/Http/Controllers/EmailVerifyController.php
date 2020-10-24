<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\MailVerify;
use App\Models\EmailVerify;
use App\Models\User;

class EmailVerifyController extends Controller
{
    public function sendVerifyCode(Request $req) {
        $findUser = User::where('email', $req->email)->first();
        if (!$findUser) {
            $findVerify = EmailVerify::where('email', $req->email)->first();
            if ($findVerify){
                $findVerify->delete();
            }
            $message = random_int(1, 99999999);
            $email_verify = new EmailVerify;
            error_log($req->email);
            $email_verify->email = $req->email;
            $email_verify->verify_code = $message;
            $email_verify->save();
            Mail::to($req->email)->send(new MailVerify($message));
            return response()->json(['msg' => '驗證碼已寄出，請到信箱查看。'], 200);
        }
        else {
            return response()->json(['msg' => '此Email已經註冊'], 402);
        }
    }
}
