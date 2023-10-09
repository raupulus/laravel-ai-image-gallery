<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        "batch_id",
        "ai",
        "role",
        "title",
        "description",
        "tags",
        "prompt",
        "negative_prompt",
        "model",
        "size",
        "size_resized",
        "resizer",
        "steps",
        "cfg_scale",
        "denoising_strength",
        "restore_faces",
        "refiner_model",
    ];

    /**
     * Relación con todas las imágenes asociadas a la colección.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'collection_id', 'id');
    }

    /**
     * Devuelve el título con longitud limitada.
     *
     * @return string
     */
    public function getTitleResumeAttribute()
    {
        return Str::limit($this->title, 50);
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->first();
    }
}
