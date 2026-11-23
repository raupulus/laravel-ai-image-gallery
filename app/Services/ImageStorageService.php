<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image as InterventionImage;
use Throwable;

class ImageStorageService
{
    private const MAX_BASE64_LENGTH = 28_000_000;

    private const MAX_BINARY_BYTES = 20_971_520;

    private const IMAGE_MAX_WIDTH = 3840;

    private const THUMBNAIL_MAX_WIDTH = 400;

    /**
     * Allowed raw image MIME types.
     *
     * @var array<int, string>
     */
    private const ALLOWED_MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/webp',
        'image/gif',
    ];

    /**
     * Validate, decode, re-encode as WebP (stripping EXIF) and store main image + thumbnail.
     *
     * @return array{image: string, thumbnail: string}
     */
    public function storeFromBase64(int $collectionId, string $base64): array
    {
        $binary = $this->decodeAndValidateBinary($base64);

        $directory = 'collections/' . $collectionId;
        $baseName = $collectionId . '_' . Str::random(30);
        $imageRelativePath = $directory . '/' . $baseName . '.webp';
        $thumbnailRelativePath = $directory . '/' . $baseName . '_thumbnail.webp';

        try {
            $original = InterventionImage::decodeBinary($binary);
            $original->orient();

            $mainImage = clone $original;
            $mainImage->scaleDown(width: self::IMAGE_MAX_WIDTH);
            $mainEncoded = (string) $mainImage->encodeUsingFileExtension('webp', quality: 90);

            $thumbnailImage = clone $original;
            $thumbnailImage->scaleDown(width: self::THUMBNAIL_MAX_WIDTH);
            $thumbnailEncoded = (string) $thumbnailImage->encodeUsingFileExtension('webp', quality: 70);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'image' => ['The image payload could not be decoded as a supported image.'],
            ]);
        }

        $disk = Storage::disk('public');
        $disk->makeDirectory($directory);
        $disk->put($imageRelativePath, $mainEncoded);
        $disk->put($thumbnailRelativePath, $thumbnailEncoded);

        return [
            'image' => $imageRelativePath,
            'thumbnail' => $thumbnailRelativePath,
        ];
    }

    /**
     * Delete specific relative paths from public storage.
     *
     * @param  array<int, string|null>  $paths
     */
    public function deletePaths(array $paths): void
    {
        $disk = Storage::disk('public');

        $cleanPaths = array_values(array_filter(
            $paths,
            static fn (?string $path): bool => is_string($path) && trim($path) !== ''
        ));

        if ($cleanPaths !== []) {
            $disk->delete($cleanPaths);
        }
    }

    /**
     * Delete an entire collection directory from public storage.
     */
    public function deleteCollectionDirectory(int $collectionId): void
    {
        Storage::disk('public')->deleteDirectory('collections/' . $collectionId);
    }

    /**
     * Decode base64 payload safely, checking length and real magic-bytes MIME type.
     */
    private function decodeAndValidateBinary(string $base64): string
    {
        if (strlen($base64) > self::MAX_BASE64_LENGTH) {
            throw ValidationException::withMessages([
                'image' => ['The image payload exceeds the maximum allowed size.'],
            ]);
        }

        $payload = $base64;

        if (str_contains($base64, ',')) {
            $parts = explode(',', $base64, 2);
            $payload = $parts[1];
        }

        $binary = base64_decode(trim($payload), true);

        if ($binary === false || $binary === '' || strlen($binary) > self::MAX_BINARY_BYTES) {
            throw ValidationException::withMessages([
                'image' => ['The image payload is invalid or exceeds 20 MB.'],
            ]);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binary);

        if (! is_string($mimeType) || ! in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw ValidationException::withMessages([
                'image' => ['Unsupported image MIME type.'],
            ]);
        }

        return $binary;
    }
}
