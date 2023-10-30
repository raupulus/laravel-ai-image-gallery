<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use function auth;

/**
 * Request para crear un nuevo tag.
 */
class CollectionVideoUpdateRequest extends FormRequest
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

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_id' => 'required|string|exists:collections,batch_id',
            //'video_id' => 'required|int',
            'url_youtube' => 'required|string',
        ];
    }
}
