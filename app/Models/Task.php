<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'params' => 'array',
    ];

    public function gallery()
    {
        return $this->hasOne(Gallery::class, 'task_id', 'task_id');
    }
}
