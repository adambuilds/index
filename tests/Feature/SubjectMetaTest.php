<?php

use App\Models\User;
use App\Models\Subject;

it('can add metadata to a subject', function () {
    $user = User::factory()->create();
    $subject = Subject::factory()->create(['name' => 'Test Subject']);

    $response = $this
        ->actingAs($user)
        ->post("subject/{$subject->id}/meta", [
            'key' => 'color',
            'value' => 'red',
        ]);

    $response->assertRedirect(route('subject.show', $subject->id, absolute: false));
    $this->assertDatabaseHas('subject_meta', [
        'subject_id' => $subject->id,
        'key' => 'color',
        'value' => 'red',
    ]);
});
