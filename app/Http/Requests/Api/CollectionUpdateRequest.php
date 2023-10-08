<?php

namespace App\Http\Requests\Api;

use App\Models\Image;
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

        try {
            $seeds = Cache::get('collection-image-update-' . $collection->id);
            $seed = $seeds[$request->get('order')];
        } catch (\Exception) {
            $seed = null;
        }

        $this->merge([
            'collection_id' => $collection->id,
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
            'image' => 'required',
            //'image' => 'required|image|mimes:jpeg,png|max:255',
            'seed' => 'nullable|int',
            'order' => 'required|int',
        ];
    }
}
