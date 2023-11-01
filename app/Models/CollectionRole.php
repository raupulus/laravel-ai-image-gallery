<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionRole extends Model
{
    protected $table = 'collection_roles';

    protected $fillable = ['slug', 'name', 'description'];


    /**
     * Relación con todas las colecciones asociadas al role actual.
     *
     * @return HasMany
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class, 'collection_role_id', 'id');
    }

}
