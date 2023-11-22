<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This is the problematic test.
     *
     * Passes: Livewire v2
     * Fails: Livewire v3 w/legacy model binding
     */
    public function test_can_save_new_user_answer()
    {
        $question = \App\Models\Question::factory()->create();

        $this->assertCount(0, \App\Models\User::all());
        $this->assertCount(0, \App\Models\Answer::all());

        Livewire::test(Quiz::class)
            ->set('user.email', fake()->email)
            ->set('user.name', fake()->name)
            ->set('answers.0.value', 'foo')
            ->call('save')
            ->assertOk();

        $this->assertCount(1, \App\Models\User::all());
        $this->assertCount(1, \App\Models\Answer::all());
        $this->assertEquals(1, \App\Models\Answer::first()->user_id);
        $this->assertEquals($question->id, \App\Models\Answer::first()->question_id);
    }

    /**
     * This test doesn't have any issues in v2 or v3.
     */
    public function test_can_save_existing_user_answer()
    {
        $user = \App\Models\User::factory()->create();
        $question = \App\Models\Question::factory()->create();

        $this->assertCount(1, \App\Models\User::all());

        // No answers
        $this->assertCount(0, \App\Models\Answer::all());

        Livewire::test(Quiz::class, ['user' => $user])
            ->set('user.email', fake()->email)
            ->set('user.name', fake()->name)
            ->set('answers.0.value', 'foo')
            ->call('save')
            ->assertOk();

        $this->assertCount(1, \App\Models\User::all());

        $this->assertCount(1, \App\Models\Answer::all());

        $this->assertEquals(1, \App\Models\Answer::first()->user_id);

        $this->assertEquals($question->id, \App\Models\Answer::first()->question_id);
    }
}
