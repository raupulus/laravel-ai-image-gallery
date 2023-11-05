<?php

namespace App\Http\Requests\Api;

use App\Models\CollectionRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use function auth;

/**
 * Request para crear un nuevo tag.
 */
class CollectionAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return !auth()->guest();

        //return auth()->id() && auth()->user()->can('store', \App\Models\Tag => =>class);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $request = request();

        $size_resized = null;

        if ($request->size && ($request->resizer === "Real-ESRGAN x4")) {
            try {
                $width = (int) explode('x', $request->size)[0];
                $height = (int) explode('x', $request->size)[1];

                $size_resized = ($width * 4 ). "x" . ($height * 4);
            } catch (\Exception $e) {
                //"resizer" => "Real-ESRGAN x4",
            }
        }

        $roleId = null;

        if ($request->role) {
            $roleModel = CollectionRole::firstOrCreate([
                'slug' => $request->role
            ], [
                'name' => ucwords(Str::replace(['-', '_'], ' ', $request->role)),
                'description' => null,
            ]);

            $roleId = $roleModel?->id;
        }


        $this->merge([
            'collection_role_id' => $roleId,
            'steps' => (string) $request->steps,
            'cfg_scale' => (string) $request->cfg_scale,
            'denoising_strength' => (string) $request->denoising_strength,
            'tags' => implode(',', $request->tags),
            'size_resized' => $size_resized,
            'restore_faces' => in_array($request->restore_faces, ['true', '1', 1]),
            'title' => Str::limit($request->title, 255),
            'description' => Str::limit($request->description, 1024),
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
            "collection_role_id" => "nullable|integer|exists:collection_roles,id",
            "role" => "nullable|string|max:255",
            "batch_id" => "required|string|max:255",
            "ai" => "nullable|string|max:127",
            "title" => "required|string|max:511",
            "description" => "nullable|string|max:1024",
            "tags" => "nullable|string|max:255",
            "prompt" => "required|string|max:4096",
            "negative_prompt" => "nullable|string|max:4096",
            "model" => "nullable|string|max:127",
            "size" => "nullable|string|max:50",
            "size_resized" => "nullable|string|max:50",
            "resizer" => "nullable|string|max:127",
            "steps" => "nullable|string|max:10",
            "cfg_scale" => "nullable|string|max:10",
            "denoising_strength" => "nullable|string|max:10",
            "restore_faces" => "bool",
            "refiner_model" => "nullable|string|max:127",
            "seeds" => "nullable|array"
        ];
    }
}
