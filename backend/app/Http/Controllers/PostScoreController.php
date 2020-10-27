<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostScore;
use App\Models\Token;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostScoreController extends Controller
{
    public function index(Request $req) {
        $findUser = Token::where('token', $req->token)->first()->user;
        if ($findUser) {
            $findScore = PostScore::where('user_id', $findUser->uid)->where('post_id', $req->post_id)->first();
            if ($findScore){
                $findScore->update(array('score' => $req->score));
                return response()->json(['msg' => 'success'], 200);
            }
            else {
                $score = new PostScore;
                $score->user_id = $findUser->uid;
                $score->post_id = $req->post_id;
                $score->score = $req->score;
                $score->save();
                return response()->json(['msg' => 'success'], 200);
            }
        }
        else {
            return response()->json(['msg' => '找不到使用者，請重新登入'], 404);
        }
    }
}
