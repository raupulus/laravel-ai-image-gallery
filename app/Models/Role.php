<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $show_name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EloquentCollection<int, User> $users
 */
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['slug', 'name', 'show_name', 'description'];

    /**
     * Usuarios asignados a este rol.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
