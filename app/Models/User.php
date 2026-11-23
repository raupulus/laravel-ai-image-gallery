<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property int|null $role_id
 * @property string $name
 * @property string $email
 * @property-read Role|null $role
 * @property-read bool $isAdmin
 * @property-read bool $canDelete
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    // ####################### Sistema de roles #######################

    /**
     * Relación con el rol del usuario.
     *
     * @return BelongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Devuelve si el usuario es de tipo administrador.
     */
    public function getIsAdminAttribute(): bool
    {
        if ($this->relationLoaded('role')) {
            return $this->role?->slug === 'admin';
        }

        if ($this->role_id === null) {
            return false;
        }

        return $this->role()->where('slug', 'admin')->exists();
    }

    /**
     * Devuelve si el usuario puede eliminar contenido
     */
    public function getCanDeleteAttribute(): bool
    {
        return $this->isAdmin;
    }
}
