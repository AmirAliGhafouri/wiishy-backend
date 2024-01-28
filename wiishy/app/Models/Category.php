<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function gifts()
    {
        $this->morphedByMany(gift::class, 'categorizables');
    }

    public function users()
    {
        $this->morphedByMany(User::class, 'categorizables');
    }
}
