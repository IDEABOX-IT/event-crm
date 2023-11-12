<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
