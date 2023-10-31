<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
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
    public function getTitleResumeAttribute(): string
    {
        return Str::limit($this->title, 50);
    }

    /**
     * Devuelve la primera imagen que encuentre para la colección.
     *
     * @return Model|HasMany|object|null
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->first();
    }

    /**
     * Devuelve la url hacia la ruta de la colección instanciada.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('collections.show', $this->id);
    }



    public static function getQueryCollection(string|null $search = null): Builder
    {
        $collections = self::select([
            'collections.*',
            DB::raw('images.collection_id')
        ])
            ->leftJoin('images', 'collections.id', '=', 'images.collection_id')
            ->whereNotNull('images.collection_id')
            ->whereNotNull('title')
            ->groupBy('images.collection_id')
            ->groupBy('collections.id');


        if ($search) {

            $collections->where(function ($q) use ($search) {
                return $q->orWhere('title', 'ILIKE', '%' . $search . '%')
                    ->orWhere('tags', 'ILIKE', '%' . $search . '%');
            });
        }

        return $collections;

    }


}
