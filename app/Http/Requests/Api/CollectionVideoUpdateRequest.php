<?php

namespace App\Http\Requests\Api;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para asociar una URL de YouTube a una colección vía API.
 */
class CollectionVideoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) ($user?->can('update', Collection::class) && $user->tokenCan('collections:write'));
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
