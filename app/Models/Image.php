<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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
    public function storeImageFromBase64(string $base64): ?UploadedFile
    {
        // Get file data base64 string
        $fileData = base64_decode(Arr::last(explode(',', $base64)));

        // Create temp file and get its absolute path
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);

        $tempFileObject = new \Illuminate\Http\File($tempFilePath);

        $uploadedFile = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );


        $thumbnailSize = 400;
        $imageSize = 3840;
        $path = 'collections/' . $this->collection->id;
        $fullPath =  storage_path('app/public/' . $path);
        $width = $height = null;
        $name = $this->collection->id . '_' . Str::random(30);
        $imageName = $name . '.webp';
        $imageThumbnailName = $name . '_thumbnail.webp';

        if (!File::isDirectory($fullPath)) {
            File::makeDirectory($fullPath, 493, true);
        }


        if (isset(getimagesize($uploadedFile)[0])) {
            $width = getimagesize($uploadedFile)[0];
        }

        if (isset(getimagesize($uploadedFile)[1])) {
            $height = getimagesize($uploadedFile)[1];
        }

        $imgOriginal = \Intervention\Image\Facades\Image::make($uploadedFile->getPathname());

        $imageResize = clone($imgOriginal);
        $imageThumbnail = clone($imgOriginal);

        ## Primer bloque para convertir imagen principal.
        if ($width > $imageSize) {
            $imageResize->resize($imageSize, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $imageResize->save($fullPath . '/' . $imageName, 90, 'webp');


        ## Primer bloque para convertir imagen de miniatura.
        if ($width > $thumbnailSize) {
            $imageThumbnail->resize($thumbnailSize, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $imageThumbnail->save($fullPath . '/' . $imageThumbnailName, 70, 'webp');


        // TODO: check if file exists before save

        $this->image = $path . '/' . $imageName;
        $this->thumbnail = $path . '/' . $imageThumbnailName;
        $this->save();

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        return $uploadedFile;
    }

}
