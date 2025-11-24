<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['url'];

    protected static function booted()
    {
        static::saving(function ($media) {
            if ($media->is_main && $media->mediable) {
                $media->mediable->media()
                    ->where('id', '!=', $media->id)
                    ->update(['is_main' => false]);
            }
        });
    }

    public function mediable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . ltrim($this->path, '/'));
    }

    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }
}
