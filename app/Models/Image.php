<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'collection_id',
        'thumbnail',
        'image',
        'seed',
        'order',
    ];

    public function urlThumbnail()
    {
        //TODO: autocompletar

        //return route('assets')
        return $this->thumbnail;
    }


    public function urlImage()
    {
        //TODO: autocompletar

        //return route('assets')
        return $this->image;
    }
}
