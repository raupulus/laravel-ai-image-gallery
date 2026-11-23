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
            'url_youtube' => [
                'required',
                'string',
                'max:255',
                'url',
                'regex:/^https:\/\/(www\.)?(youtube\.com\/(watch\?v=|embed\/|shorts\/)|youtube-nocookie\.com\/embed\/|youtu\.be\/)[A-Za-z0-9_-]{6,32}([?&].*)?$/',
            ],
        ];
    }
}
