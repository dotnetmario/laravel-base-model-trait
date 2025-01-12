<?php

namespace App\Models;

use App\Traits\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes, BaseModelTrait;

    protected $fillable = [
        'name',
        'joined',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
