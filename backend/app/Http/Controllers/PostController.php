<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Token;
use App\Models\PostScore;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function getAllPosts(Request $req) {
        $posts = Post::all()->sortByDesc('updated_at');
        $filteredPosts = $posts->skip(($req->page / 4) * 30)->take(30);
        if ($filteredPosts->count() > 0){
            $newPosts = [];
            $users = [];
            $scores = [];
            $index = 0;
            foreach($filteredPosts as $key => $val){
                $newPosts[$index] = $val;
                $users[$index] = $val->user;
                $scores[$index] = $val->scores->avg('score');
                $index++;
            }
            return response()->json(['posts' => $newPosts, 'users' => $users, 'scores' => $scores, 'totalCounts' => $posts->count()], 200);
        }
        else {
            return response()->json(['msg' => '沒有文章'], 404);
        }        
    }

    public function findPostById(Request $req, $id) {
        $post = Post::where('pid', $id)->first();
        if ($post){
            $user = $post->user;
            $score = null;
            if ($req->token != 'logout') {
                $findUser = Token::where('token', $req->token)->first()->user;
                $findScore = PostScore::where('user_id', $findUser->uid)->where('post_id', $id)->first();
                if (!$findScore){
                    $score = 0;
                }
                else {
                    $score = $findScore->score;
                }
            }
            return response()->json(['post' => $post, 'user' => $user, 'score' => $score], 200);
        }
        else {
            return response()->json(['msg' => '找不到此文章'], 404);
        }
    }

    public function createPost(Request $req) {
        $findUser = Token::where('token', $req->token)->first();
        if ($findUser){
            $user = $findUser->user;
            $post = new Post;
            $post->fill($req->all());
            if ($req->cover == '') {
                $post->cover = 'cover.jpg';
            }
            else {
                $imagePath = null;
                $imagePath = $this->CheckSameImage($req->cover, $req->storePath);
                if (!$imagePath){
                    $post->cover = $req->cover->store($req->storePath);
                }
                else {
                    $post->cover = $imagePath;
                }
            }
            $post->user_id = $user->uid;
            $post->save();
            return response()->json(['msg' => '新增文章成功'], 200);
        }
        else {
            return response()->json(['msg' => '此帳號已經從別地方登入'], 402);
        }
    }

    public function updatePost(Request $req) {
        $update_data = null;
        $imagePath = null;
        if ($req->cover != ''){
            $imagePath = $this->CheckSameImage($req->cover, $req->storePath);
            if ($imagePath == false) {
                $imagePath = $req->cover->store($req->storePath);
            }
        }
        if ($imagePath != null){
            $update_data = array('title' => $req->title, 'content' => $req->content, 'class' => $req->class, 'cover' => $imagePath);
        }
        else {
            $update_data = array('title' => $req->title, 'content' => $req->content, 'class' => $req->class);
        }
        Post::where('pid', $req->post_id)->update($update_data);
        return response()->json(['msg' => '更新文章成功'], 200);
    }

    public function deletePost(Request $req, $id){
        $findUser = Token::where('token', $req->token)->first()->user;
        if ($findUser){
            $findPostUser = Post::where('pid', $id)->first()->user;
            if ($findUser->uid == $findPostUser->uid){
                Post::where('pid', $id)->delete();
                return response()->json(['msg' => '刪除文章成功'], 200);
            }
            else {
                return response()->json(['msg' => '你沒有權限刪除文章'], 402);
            }
        }
        else {
            return response()->json(['msg' => '找不到使用者'], 404);
        }
    }

    // 確認Storage是否有相同圖片 有的話就沿用那個圖片，md5()可以把圖片加密 以此來對比是否相同的圖片
    public function CheckSameImage($image, $storePath) {
        $md5_img = md5_file($image);
        foreach(Storage::files($storePath) as $filename){
            $md5_storage_img = md5(Storage::get($filename));
            if ($md5_img == $md5_storage_img){
                return str_replace("C:\Users\k7766\Desktop\laravel app\backend\storage\app\\", '', Storage::path($filename));
                break;
            }
        }
        return false;
    }
}
