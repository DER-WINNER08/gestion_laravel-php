<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title' , "nom" , "prix" , 'description',"category_id", "user_id",'completed'];
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
