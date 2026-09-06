<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('tools.create');
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

        $data['user_id'] = auth('api')->id();

        $post = Post::create($data);


        foreach ($images as $index => $image) {

            $path = $image->store('posts', 'public');

            $post->images()->create([
                'path' => $path,
                'sort_order' => $index,
                'is_cover' => $index === 0,
            ]);
        }
        return redirect()->route('home')->with('success', 'پست با موفقیت ایجاد شد.');
    }
}
