<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    //GET|HEAD  | admin/category
    public function index()
    {
        $categorys = (new Category)->tree();
        return view('admin.category.index')->with('data',$categorys);
    }

    public function changeorder()
    {
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类排序更新成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }


    // GET|HEAD  | admin/category/create
    public function create()
    {
        $data = Category::where('cate_pid', 0)->get();
        return view('admin/category/add', compact('data'));
    }


    //POST   | admin/category
    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'cate_name'=>'required',
        ];

        $message = [
            'cate_name.required'=>'分类名称不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Category::create($input);
            if($re){
                return redirect('admin/category');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //GET|HEAD | admin/category/{categore}/edit
    public function edit($cate_id)
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid', 0)->get();

        return view('admin.category.edit', compact('field','data'));
    }

    //PUT|PATCH  | admin/category/{categore}
    public function update($cate_id)
    {
        $input = Input::except('_token','_method');
        $rs = Category::where('cate_id',$cate_id)->update($input);
        if($rs) {
            return redirect('admin/category');
        } else {
            return back()->with('errors','分类信息更新失败,请重试!');
        }

    }

    //DELETE     | admin/category/{categore}
    public function destroy($cate_id)
    {
        $re = Category::where('cate_id',$cate_id)->delete();
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);

        if ($re) {
            $data = [
                'status' => 0,
                'msg' =>'分类删除成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' =>'分类删除是啊比',
            ];
        }
        return $data;

    }


    //GET|HEAD   | admin/category/{categore}
    public function show()
    {

    }



}
