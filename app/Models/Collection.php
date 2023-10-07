<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        "batch_id",
        "ai",
        "role",
        "title",
        "description",
        "tags",
        "prompt",
        "negative_prompt",
        "model",
        "size",
        "size_resized",
        "resizer",
        "steps",
        "cfg_scale",
        "denoising_strength",
        "restore_faces",
        "refiner_model",
    ];
}
