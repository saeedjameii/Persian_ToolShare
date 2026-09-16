<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        return view('tools.create', compact('categories'));
    }

    public function createPost(Request $request){
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'category_id' => 'required|exists:categories,id',

            'condition' => 'required|string',
            'location' => 'required|string',

            'first_day_price' => 'nullable|numeric|min:0',
            'extra_day_price' => 'nullable|numeric|min:0',

            'available_from' => 'nullable|date',
            'available_untill' => 'nullable|date|after_or_equal:available_from',

            'images' => 'required|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

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
        
        $query = Post::with(['category', 'images', 'user'])->latest();

        if($request->filled('search')){
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }
        if($request->filled('category_id')){
            $query->where('category_id', $request->category_id);
        }

        $posts = $query->paginate(8)->withQueryString();

        $categories = Category::whereDoesntHave('children')->get();

        return view('tools.index', compact('posts', 'categories'));
    }

    public function myPosts(){
        $posts = auth('api')->user()->posts()->with(['category', 'images'])->latest()->get();
        return view('tools.my-posts', compact('posts'));
    }

    public function show(Post $post){
        $post->load(['category', 'images', 'user']);

        return view('tools.show', compact('post'));
    }

    public function edit(Post $post)
    {

        Gate::authorize('update', $post);
        
        $categories = Category::WhereDoesntHave('children')->with('parent')->get();

        return view('tools.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post){
        Gate::authorize('update', $post);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'category_id' => 'required|exists:categories,id',

            'condition' => 'required|string',
            'location' => 'required|string',

            'first_day_price' => 'nullable|numeric|min:0',
            'extra_day_price' => 'nullable|numeric|min:0',

            'available_from' => 'nullable|date',
            'available_untill' => 'nullable|date|after_or_equal:available_from',
        ]);

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
