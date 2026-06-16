<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    const ROLE_ADMIN = 'admin';
    const ROLE_GESTOR = 'gestor';
    const ROLE_COLABORADOR = 'colaborador';
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //SETOR
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    //ADM
    public function isAdmin()
    {
        return $this->role === 'administrador';
    }

    //GESTOR
    public function isGestor()
    {
        return $this->role === 'gestor';
    }

    //FUNCIONARIO
    public function isColaborador()
    {
        return $this->role === 'colaborador';
    }
}
