<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    //
    protected $fillable =['colocation_id','email','token','status','expires_at'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    public function colocation(){
        return $this->belongsTo(Colocation::class);
    }
}



