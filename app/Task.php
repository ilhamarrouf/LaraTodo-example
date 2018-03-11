<?php

namespace App;

use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'title', 'description',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
