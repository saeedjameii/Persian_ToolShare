<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\IranProvince;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
    public function home(){
        $latestPosts = Post::with(['category', 'images', 'user'])->latest()->take(4)->get();
        return view('home', compact('latestPosts'));
    }
    public function create()
    {
        $categories = Category::whereDoesntHave('children')->with('parent')->get();
        $provinces = IranProvince::orderBy('name')->get(['id', 'name']);
        return view('tools.create', compact('categories', 'provinces'));
    }

    public function citiesByProvince(IranProvince $province){
        return $province->cities()->orderBy('name')->get(['id', 'name']);
    }

    public function createPost(PostRequest $request){
        $data = $request->validated();

        $images = $request->file('images');

        unset($data['images']);


        $token = $request->cookie('token');

        $user = JWTAuth::setToken($token)->authenticate();

        $data['user_id'] = $user->id;
        
        $post = Post::create($data);

        foreach ($images as $image) {

            $path = $image->store('posts', 'public');

            $post->images()->create([
                'path' => $path,
            ]);
        }
        return redirect()->route('home')->with('success', 'پست با موفقیت ایجاد شد.');
    }
    public function index(Request $request){
        
        $query = Post::with(['category', 'images', 'user', 'province', 'city'])->latest();

        if($request->filled('search')){
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }
        if($request->filled('category_id')){
            $query->where('category_id', $request->category_id);
        }

        if($request->filled('province_id')){
            $query->where('province_id', $request->province_id);
        }

        if($request->filled('city_id')){
            $query->where('city_id', $request->city_id);
        }

        $posts = $query->paginate(8)->withQueryString();

        $categories = Category::whereDoesntHave('children')->get();

        $provinces = IranProvince::orderBy('name')->get(['id', 'name']);

        return view('tools.index', compact('posts', 'categories', 'provinces'));
    }

    public function myPosts(){
        $posts = auth('api')->user()->posts()->with(['category', 'images'])->latest()->get();
        return view('tools.my-posts', compact('posts'));
    }

    public function show(Post $post){
        $provinces = IranProvince::orderBy('name')->get(['id', 'name']);
        $post->load(['category', 'images', 'user', 'province', 'city']);

        return view('tools.show', compact('post', 'provinces'));
    }

    public function edit(Post $post)
    {

        Gate::authorize('update', $post);
        
        $categories = Category::WhereDoesntHave('children')->with('parent')->get();
        $provinces = IranProvince::orderBy('name')->get(['id', 'name']);

        return view('tools.edit', compact('post', 'categories', 'provinces'));
    }

    public function update(PostRequest $request, Post $post){
        Gate::authorize('update', $post);

        $data = $request->validated();

        $post->update($data);

        return redirect()->route('posts.show', $post)->with('success', 'پست با موفقیت ویرایش شد.');
    }

    public function destroy(Post $post){
        Gate::authorize('delete', $post);

        try{
            $post->delete();
            return redirect()->route('posts.index')->with('success', 'پست مورد نظر با موفقیت حذف شد.');
        } catch(\Throwable $e){
            return redirect()->back()->with('error', 'پست شما حذف نشد لطفا دوباره تلاش کنید');
        }
    }
}
