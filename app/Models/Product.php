<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'price', 'description', 'category', 'image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
    */
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = array();
}
