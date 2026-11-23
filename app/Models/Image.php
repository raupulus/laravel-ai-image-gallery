<?php

namespace App\Models;

use App\Services\ImageStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'collection_id',
        'thumbnail',
        'image',
        'seed',
        'order',
    ];

    /**
     * Relación con la colección de imágenes.
     *
     * @return BelongsTo
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id', 'id');
    }

    public function getUrlThumbnailAttribute()
    {
        return asset('storage/' . $this->thumbnail);
    }


    public function getUrlImageAttribute()
    {
        return asset('storage/' . $this->image);
    }


    /**
     * Recibe un string en base64 y lo convierte en un archivo.
     *
     * @param string $base64 Cadena en base64
     *
     * @return UploadedFile|null
     */
    /**
     * Almacena la imagen recibida en base64 y su miniatura en formato WebP.
     */
    public function storeImageFromBase64(string $base64): ?UploadedFile
    {
        $paths = app(ImageStorageService::class)->storeFromBase64((int) $this->collection_id, $base64);

        $this->image = $paths['image'];
        $this->thumbnail = $paths['thumbnail'];
        $this->save();

        return null;
    }

    /**
     * Elimina los archivos físicos asociados y el registro en base de datos.
     */
    public function safeDelete(): void
    {
        app(ImageStorageService::class)->deletePaths([$this->image, $this->thumbnail]);

        $this->delete();
    }

}
