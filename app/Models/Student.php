<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'gender', 'data_of_birth'];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'registrations');
    }
}
