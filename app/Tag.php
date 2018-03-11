<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name',
    ];
    
    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }
}
