<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.index', compact('categories'));
    }

    public function create(){

        $categories = Category::all();

        return view('categories.create', compact('categories'));
    }

    public function store(Request $request){
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|max:250|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'دسته‌بندی با موفقیت ایچاد شد');
    }

    public function edit(Category $category){
            // $categories = Category::where('id', '!=', $category->id)->get();
        $excludeId = $category->descendants()->pluck('id')->push($category->id);
        $categories = Category::whereNotIn('id', $excludeId)->get();


        return view('categories.edit',compact('category', 'categories'));
    }

    public function update(Request $request, Category $category){
        $data = $request->validate([
            'name' => 'required|max:250|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'دسته‌بندی مورد نظر با موفقیت ویرایش شد');
    }

    public function destroy(Category $category){

        try{
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'دسته‌بندی مورد نظر با موفقیت حذف شد');
        }
        catch(QueryException $e){
            return redirect()->route('categories.edit')->with('error', 'این دسته‌بندی قابل حذف نیست؛ ابتدا پست‌های مربوط به آن را مدیریت کنید.');
        }
    }

}
