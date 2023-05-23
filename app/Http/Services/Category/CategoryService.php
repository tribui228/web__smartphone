<?php

namespace App\Http\Services\Category;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CategoryService
{
    public function create($request){
       try{
            Category::create([
                'name' => (string) $request->input('name'),
                'parent_id' => (int) $request->input('parent_id'),
                'description' => (string) $request->input('description'),
                'active' => (string) $request->input('active'),
                'slug' => Str::slug($request->input('name'), '-')
            ]);
            Session::flash('success', 'Tạo danh mục thành công');
       } catch (\Exception $err){
            Session::flash('error', $err->getMessage());
            return false;
       }
       return true;
    }

    public function getParent(){
        return Category::where('parent_id', 0)->cursorPaginate(100);
    }

    public function getAll(){
        //return Category::orderbyDesc('id', '>', 100)->cursorPaginate(100);
        return Category::orderbyDesc('id', '>', 100)->cursorPaginate(100);
    }

    public function destroy($request){
        $category = Category::where('id', $request->input('id'))->orWhere('parent_id', $request->input('id'))->get();
        if($category){
            $category->delete();
            return true;
        }
        return false;
    }

    public function update($request, $category) : bool{
       try{
           $category->name = (string) $request->input('name');
           $category->parent_id = (int) $request->input('parent_id');
           $category->description = (string) $request->input('description');
           $category->active = (string) $request->input('active');
           $category->save();

           Session::flash('success', 'Cập nhật danh mục thành công');
       } catch (\Exception $err) {
           Session::flash('error', $err->getMessage());
           return false;
       }
       return true;
    }

    public function count(){
        return Category::query()->count();
    }
}
