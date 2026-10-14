<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['path','thumb_path','sort','width','height','alt'];

    protected $appends = ['url','thumb_url'];

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->thumb_path
            ? Storage::disk('public')->url($this->thumb_path)
            : $this->url;
    }
}
