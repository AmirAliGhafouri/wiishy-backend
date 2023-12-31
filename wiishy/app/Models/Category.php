<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function gifts()
    {
        $this->morphedByMany(gift::class, 'categorizables');
    }

    public function users()
    {
        $this->morphedByMany(User::class, 'categorizables');
    }
}
