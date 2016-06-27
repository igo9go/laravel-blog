<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use App\Http\Model\Category;
use App\Http\Model\Links;

class ArticleController extends CommonController
{
    public function index()
    {
        //点击量最高的6篇文章（站长推荐）
        $pics = Article::orderBy('art_view','desc')->take(6)->get();

        //图文列表5篇（带分页）
        $data = Article::orderBy('art_time','desc')->paginate(5);

        //友情链接
        $links = Links::orderBy('link_order','asc')->get();

        return view('home.index',compact('pics','data','links'));
    }

    public function cate($cate_id)
    {
        //图文列表4篇（带分页）
        $data = Article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(4);

        //查看次数自增
        Category::where('cate_id',$cate_id)->increment('cate_view');

        //当前分类的子分类
        $submenu = Category::where('cate_pid',$cate_id)->get();

        $field = Category::find($cate_id);
        return view('home.list',compact('field','data','submenu'));
    }

    public function article($art_id)
    {
        $field = Article::Join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();

        //查看次数自增
        Article::where('art_id',$art_id)->increment('art_view');

        $article['pre'] = Article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
        $article['next'] = Article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();

        $data = Article::where('cate_id',$field->cate_id)->orderBy('art_id','desc')->take(6)->get();

        return view('home.new',compact('field','article','data'));
    }


    public function vote()
    {

    }

    public function redis()
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
    }
}
