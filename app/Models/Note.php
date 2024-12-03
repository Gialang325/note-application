<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Note extends Model
{
    protected $table = 'note';
    
    protected $fillable = [
        'title',
        'text',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($note) {
            $note->response()->delete();
        });

        static::creating(function ($note) {
            $note->slug = Str::slug($note->title, '-');
        });

        static::saving(function ($note) {
            if ($note->isDirty('slug')) {
                $note->slug = Str::slug($note->title, '-');
            }
        });
    }
}
