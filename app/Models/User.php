<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
    ];


    ######################## Sistema de roles #######################


    /**
     * Devuelve si el usuario es de tipo administrador.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role_id === 1;
    }

    /**
     * Devuelve si el usuario puede eliminar contenido
     *
     * @return bool
     */
    public function getCanDeleteAttribute(): bool
    {
        return $this->isAdmin;
    }
}
