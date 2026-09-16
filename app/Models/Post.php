<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function province(){
        return $this->belongsTo(IranProvince::class, 'province_id');
    }

    public function city(){
        return $this->belongsTo(IranCity::class, 'city_id');
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }
}
