<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function answer(User $user)
    {
        return $user->id ?
            Answer::firstOrCreate(['user_id' => $user->id, 'question_id' => $this->id]) :
            new Answer(['question_id' => $this->id]);
    }
}
