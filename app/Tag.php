<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];
    
    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }
}
