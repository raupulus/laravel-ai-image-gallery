<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionRole extends Model
{
    protected $table = 'collection_roles';

    protected $fillable = ['name', 'description'];


    /**
     * Relación con todas las colecciones asociadas al role actual.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collections()
    {
        return $this->hasMany(Collection::class, 'collection_role_id', 'id');
    }

}
