<?php

namespace App\Http\Requests\Api;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Request para adjuntar una imagen a una colección vía API.
 */
class CollectionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $collection = $this->route('collection');

        if (! $collection instanceof Collection) {
            return false;
        }

        return (bool) ($user?->can('update', $collection) && $user->tokenCan('collections:write'));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $collection = $this->route('collection');
        $order = $this->input('order');
        $seed = null;

        if ($collection instanceof Collection && (is_int($order) || (is_string($order) && is_numeric($order)))) {
            try {
                $seeds = Cache::get('collection-image-update-' . $collection->id);

                if (is_array($seeds) && array_key_exists((int) $order, $seeds)) {
                    $seed = $seeds[(int) $order];
                }
            } catch (Throwable $e) {
                Log::warning('Failed to read cached seeds for collection image upload', [
                    'collection_id' => $collection->id,
                    'order' => $order,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        $this->merge([
            'collection_id' => $collection instanceof Collection ? $collection->id : null,
            'seed' => $seed,
            'order' => $order,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'collection_id' => 'required|integer|exists:collections,id',
            'image' => 'required|string|max:28000000',
            'seed' => 'nullable|integer',
            'order' => 'required|integer|min:0|max:1000',
        ];
    }
}
