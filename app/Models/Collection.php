<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $collection_role_id
 * @property string $batch_id
 * @property string|null $ai
 * @property string|null $role
 * @property string $title
 * @property string|null $description
 * @property string|null $tags
 * @property string $prompt
 * @property string|null $negative_prompt
 * @property string|null $model
 * @property string|null $size
 * @property string|null $size_resized
 * @property string|null $resizer
 * @property string|null $steps
 * @property string|null $cfg_scale
 * @property string|null $denoising_strength
 * @property bool $restore_faces
 * @property string|null $refiner_model
 * @property string|null $url_youtube
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EloquentCollection<int, Image> $images
 * @property-read CollectionRole|null $collectionRole
 */
class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    protected $fillable = [
        'collection_role_id',
        'batch_id',
        'ai',
        'role',
        'title',
        'description',
        'tags',
        'prompt',
        'negative_prompt',
        'model',
        'size',
        'size_resized',
        'resizer',
        'steps',
        'cfg_scale',
        'denoising_strength',
        'restore_faces',
        'refiner_model',
        'url_youtube',
    ];

    /**
     * Attribute type casts.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'collection_role_id' => 'integer',
        'restore_faces' => 'boolean',
    ];

    /**
     * Relación con todas las imágenes asociadas a la colección.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'collection_id', 'id');
    }

    /**
     * Relación con el rol temático asociado a la colección.
     */
    public function collectionRole(): BelongsTo
    {
        return $this->belongsTo(CollectionRole::class, 'collection_role_id', 'id');
    }

    /**
     * Alias heredado de la relación collectionRole().
     */
    public function roleTmp(): BelongsTo
    {
        return $this->collectionRole();
    }

    /**
     * Devuelve si las proporciones de las imágenes son horizontal.
     */
    public function getIsHorizontalAttribute(): bool
    {
        $size = $this->size;

        if (! $size) {
            return true;
        }

        $width = (int) explode('x', $size)[0];
        $height = (int) explode('x', $size)[1];

        return $width > $height;
    }

    /**
     * Devuelve si las proporciones de las imágenes son vertical.
     */
    public function getIsVerticalAttribute(): bool
    {
        return ! $this->isHorizontal;
    }

    /**
     * Devuelve el título con longitud limitada.
     */
    public function getTitleResumeAttribute(): string
    {
        return Str::limit($this->title, 50);
    }

    /**
     * Prepara los tags asociados y los devuelve en un array.
     */
    public function getTagsArrayAttribute(): array
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    /**
     * Devuelve la primera imagen que encuentre para la colección.
     */
    public function getPrimaryImageAttribute(): ?Image
    {
        if ($this->relationLoaded('images')) {
            return $this->images->first();
        }

        /** @var Image|null $image */
        $image = $this->images()->orderBy('order')->orderBy('id')->first();

        return $image;
    }

    /**
     * Devuelve la url hacia la ruta de la colección instanciada.
     */
    public function getUrlAttribute(): string
    {
        return route('collections.show', $this->id);
    }

    /**
     * Devuelve la URL de inserción en modo privacidad mejorada (youtube-nocookie.com) o null si no es válida.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (empty($this->url_youtube)) {
            return null;
        }

        if (preg_match('/^https:\/\/(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtube-nocookie\.com\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{6,32})/', $this->url_youtube, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/'.$matches[1];
        }

        return null;
    }

    public static function getQueryCollection(?string $search = null): Builder
    {
        $collections = self::query()
            ->whereHas('images')
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->with([
                'images' => fn ($query) => $query->orderBy('order')->orderBy('id'),
            ])
            ->withCount('images');

        if ($search !== null && $search !== '') {
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);

            $collections->where(function (Builder $q) use ($escaped): void {
                $q->orWhere('title', 'ILIKE', '%'.$escaped.'%')
                    ->orWhere('tags', 'ILIKE', '%'.$escaped.'%');
            });
        }

        return $collections;
    }
}
