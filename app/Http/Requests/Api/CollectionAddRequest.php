<?php

namespace App\Http\Requests\Api;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Request para crear o actualizar una colección vía API.
 */
class CollectionAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) ($user?->can('create', Collection::class) && $user->tokenCan('collections:write'));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $size = $this->input('size');
        $resizer = $this->input('resizer');
        $sizeResized = null;

        if (is_string($size) && $resizer === 'Real-ESRGAN x4' && preg_match('/^(\d+)\s*[xX]\s*(\d+)$/', trim($size), $matches)) {
            $sizeResized = ((int) $matches[1] * 4).'x'.((int) $matches[2] * 4);
        }

        $tags = $this->input('tags');
        if (is_array($tags)) {
            $cleanTags = array_filter($tags, static fn ($tag): bool => is_scalar($tag) && trim((string) $tag) !== '');
            $tags = implode(',', array_map(static fn ($tag): string => trim((string) $tag), $cleanTags));
        } elseif (! is_string($tags)) {
            $tags = null;
        }

        if (is_string($tags)) {
            $tags = $tags !== '' ? Str::limit($tags, 255, '') : null;
        }

        $title = $this->input('title');
        $description = $this->input('description');

        $this->merge([
            'steps' => $this->normalizeScalarToString($this->input('steps')),
            'cfg_scale' => $this->normalizeScalarToString($this->input('cfg_scale')),
            'denoising_strength' => $this->normalizeScalarToString($this->input('denoising_strength')),
            'tags' => $tags,
            'size_resized' => $sizeResized,
            'restore_faces' => $this->has('restore_faces') ? $this->boolean('restore_faces') : false,
            'title' => is_string($title) ? Str::limit(trim($title), 511, '') : $title,
            'description' => is_string($description) ? Str::limit(trim($description), 1024, '') : $description,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'collection_role_id' => 'nullable|integer|exists:collection_roles,id',
            'role' => 'nullable|string|max:255',
            'batch_id' => 'required|string|max:255',
            'ai' => 'nullable|string|max:127',
            'title' => 'required|string|max:511',
            'description' => 'nullable|string|max:1024',
            'tags' => 'nullable|string|max:255',
            'prompt' => 'required|string|max:4096',
            'negative_prompt' => 'nullable|string|max:4096',
            'model' => 'nullable|string|max:127',
            'size' => 'nullable|string|max:50',
            'size_resized' => 'nullable|string|max:50',
            'resizer' => 'nullable|string|max:127',
            'steps' => 'nullable|string|max:50',
            'cfg_scale' => 'nullable|string|max:50',
            'denoising_strength' => 'nullable|string|max:50',
            'restore_faces' => 'bool',
            'refiner_model' => 'nullable|string|max:127',
            'seeds' => 'nullable|array|max:100',
            'seeds.*' => 'integer',
        ];
    }

    private function normalizeScalarToString(mixed $value): ?string
    {
        if ($value === null || $value === '' || ! is_scalar($value)) {
            return null;
        }

        return (string) $value;
    }
}
