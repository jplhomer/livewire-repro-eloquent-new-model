<?php

namespace App\Http\Livewire;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Quiz extends Component
{
    public User $user;
    public Collection $questions;
    public Collection $answers;

    public function rules()
    {
        return [
            'user.email' => 'required|email',
            'user.name' => 'required',
            'user.password' => 'required',
            'answers.*.question_id' => 'required',
            'answers.*.value' => 'required',
        ];
    }

    public function mount(?User $user = null)
    {
        // In this case, a user may start a quiz anonymously or authenticated.
        $this->user = $user ?? new User();

        $this->questions = Question::all();

        // Collect the answers for the user. If one exists, use it. Otherwise, create a new instance.
        $this->answers = $this->questions->map(fn ($question) => $question->answer($this->user));
    }

    public function save()
    {
        // Save the user if they are not already saved.
        if (! $this->user->id) {
            // At this point, the user would have entered their info.
            $this->user->password = 'password';
            $this->user->save();
        }

        $this->answers->each(function ($answer) {
            $answer->user_id = $this->user->id;
            $answer->save();
        });
    }

    public function render()
    {
        return view('livewire.quiz');
    }
}
