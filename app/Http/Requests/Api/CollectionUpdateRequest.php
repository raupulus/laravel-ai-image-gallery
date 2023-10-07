<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use function auth;

/**
 * Request para crear un nuevo tag.
 */
class CollectionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return ! auth()->guest();

        //return auth()->id() && auth()->user()->can('store', \App\Models\Tag::class);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $request = request();

        $collection = $request->route()->parameter('collection');

        // TODO: Crear thumbnail
        // TODO: Imagen principal optimizada 1280x1024 máximo en webp


        $thumbnailPath = '';
        $imagePath = '';

        try {
            $seeds = Cache::get('collection-image-update-' . $collection->id);
            $seed = $seeds[$request->get('order')];
        } catch (\Exception) {
            $seed = null;
        }

        $this->merge([
            'collection_id' => $collection->id,
            'thumbnail' => $thumbnailPath,
            'image' => $imagePath,
            'seed' => $seed,
            'order' => $request->get('order'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'collection_id' => 'required|int|exists:collections,id',
            'thumbnail' => 'nullable|string|max:255',
            'image' => 'required|string|max:255',
            'seed' => 'nullable|int',
            'order' => 'required|int',
        ];
    }
}
