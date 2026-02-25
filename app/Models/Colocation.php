<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [ 'name','status' ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'colocation_user')
            ->withPivot('internal_role', 'joined_at', 'left_at')
            ->withTimestamps();
    }


    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }


    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
