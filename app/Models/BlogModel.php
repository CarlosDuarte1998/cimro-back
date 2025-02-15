<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    protected $fillable = ['image', 'title', 'description','slug','category','user_id'];
    protected $table = 'blog';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
