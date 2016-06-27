<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class VoteController extends Controller
{

    public function like()
    {
        if(Request::ajax()) {
            $time = time();
            $input = array(
                'art_id' => Request::get('art_id'),

            );
            $rules = array(
                'art_id' => 'required'
            );
            $validator = Validator::make($input, $rules);
            if ( $validator->fails() ) {
                return Response::json([
                    'status' => false,
                    'info' => $validator->getMessageBag()->toArray()
                ]);

            }
            $art_id = Request::get('art_id');

            $num = Redis::hget('article', $art_id);
            $ip = Request::getClientIp();

            $voteTime = Redis::hget('voted:'.$art_id, $ip);
            $spendTime = intval($time - $voteTime);
            //投票时间小于一个小时
            if ($spendTime < 3 ) {
                return Response::json([
                    'status' => false,
                    'art_id' => $art_id,
                    'vote_num' => $num,

                ]);
            } else {
                Redis::hset('voted:'.$art_id, $ip, $time);

                if ($num) {
                    Redis::hincrby('article', $art_id, 1);
                } else {
                    Redis::hset('article', $art_id, 1);
                }
            }

            $num = Redis::hget('article', $art_id);
            return Response::json([
                'status' => true,
                'art_id' => $art_id,
                'vote_num' => $num,
            ]);
        }
    }

    public function hate()
    {
        if(Request::ajax()) {
            $time = time();
            $input = array(
                'art_id' => Request::get('art_id'),

            );
            $rules = array(
                'art_id' => 'required'
            );
            $validator = Validator::make($input, $rules);
            if ( $validator->fails() ) {
                return Response::json([
                    'status' => false,
                    'info' => $validator->getMessageBag()->toArray()
                ]);

            }
            $art_id = Request::get('art_id');

            $num = Redis::hget('article', $art_id);
            $ip = Request::getClientIp();

            $voteTime = Redis::hget('voted:'.$art_id, $ip);
            $spendTime = intval($time - $voteTime);
            //投票时间小于一个小时
            if ($spendTime < 3 ) {
                return Response::json([
                    'status' => false,
                    'art_id' => $art_id,
                    'vote_num' => $num,

                ]);
            } else {
                Redis::hset('voted:'.$art_id, $ip, $time);

                if ($num) {
                    Redis::hincrby('article', $art_id, -1);
                } else {
                    Redis::hset('article', $art_id, 1);
                }
            }

            $num = Redis::hget('article', $art_id);
            return Response::json([
                'status' => true,
                'art_id' => $art_id,
                'vote_num' => $num,
            ]);
        }

    }


    public function qq()
    {
        $key = 'user:name:6';

        $user = User::find(6);
        if($user){
            //将用户名存储到Redis中
            Redis::set($key,$user->name);
        }

        //判断指定键是否存在
        if(Redis::exists($key)){
            //根据键名获取键值
            dd(Redis::get($key));
        }
    }

    public function sadd()
    {
        $key = 'posts:title';

        $posts = Post::all();
        foreach ($posts as $post) {
            //将文章标题存放到集合中
            Redis::sadd($key,$post->title);
        }

//获取集合元素总数(如果指定键不存在返回0)
        $nums = Redis::scard($key);

        if($nums>0){
            //从指定集合中随机获取三个标题
            $post_titles = Redis::srandmember($key,3);
            dd($post_titles);
        }

//redis 管道  多个任务 /锁
        Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 1000; $i++) {
                $pipe->set("key:$i", $i);
            }
        });
    }


    public function store(Request $request)

    {

        $poll = new Poll;



        $poll->date = Input::get('date');



        if ($poll->save()) {

            return response()->json(array(

                'status' => 1,

            'msg' => 'ok',

        ));

    } else {

            return Redirect::back()->withInput()->withErrors('保存失败！');

        }
    }

    public function update()
    {
        if(Request::ajax()) {

            $input = array(
                'name' => Request::get('name'),
                'slug' => Request::get('name'),
                'description' => Request::get('name')
            );
            $rules = array(
                'name' => 'required'
            );
            $validator = Validator::make($input, $rules);
            if ( $validator->fails() ) {
                return Response::json([
                    'success' => false,
                    'info' => $validator->getMessageBag()->toArray()
                ]);

            }
            $id = Request::get('id');
            $tag = $this->tag->find($id);
            foreach (array_keys(array_except($this->fields, ['tag'])) as $field) {
                $tag->$field = Request::get($field);
            }
            $tag->save();
            $tag = $this->tag->find($id);
            return Response::json([
                'success' => true,
                'name' => $tag ->tag,
                'slug' => $tag ->slug,
                'description' => $tag ->description,
                'info' => "The tag '$tag->tag' was updated."
            ]);
        }
    }

    public function test()
    {

        Cache::put('11','22');
        dd(Cache::get('11'));
    }
}
