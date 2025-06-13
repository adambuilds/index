<?php

use App\Models\User;
use App\Models\Subject;

it('can add a tag to a subject', function () {
    $user = User::factory()->create();
    $subject = Subject::factory()->create(['name' => 'Test Subject']);

    $response = $this
        ->actingAs($user)
        ->post("subject/{$subject->id}/tags", [
            'name' => 'Important',
        ]);

    $response->assertRedirect(route('subject.show', $subject->id, absolute: false));
    $this->assertDatabaseHas('tags', [
        'name' => 'Important',
    ]);
    $this->assertDatabaseHas('subject_tag', [
        'subject_id' => $subject->id,
    ]);
});
