<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EloquentCollection<int, Collection> $collections
 */
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
