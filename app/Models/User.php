<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name','email','password','role_id','reputation_score','is_banned'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password','remember_token'];

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
            'is_banned'=>'boolean',
            'reputation_score'=>'integer'
        ];
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'colocation_user')
                    ->withPivot('internal_role', 'joined_at', 'left_at')
                    ->withTimestamps();
    }

    public function settlementsAsPayer()
    {
        return $this->hasMany(Settlement::class, 'payer_id');
    }

    public function settlementsAsReceiver()
    {
        return $this->hasMany(Settlement::class, 'receiver_id');
    }

      public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isBanned(): bool
    {
        return (bool) $this->is_banned;
    }
}


