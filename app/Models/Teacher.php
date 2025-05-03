<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'telefone', 'formation', 'gender', 'data_of_birth'];
    
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
 }
