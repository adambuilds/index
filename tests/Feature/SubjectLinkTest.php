<?php

use App\Models\User;
use App\Models\Subject;

it('can add a link to a subject', function () {
    $user = User::factory()->create();
    $subject = Subject::factory()->create(['name' => 'Test Subject']);

    $response = $this
        ->actingAs($user)
        ->post("subject/{$subject->id}/links", [
            'title' => 'Home',
            'url' => 'https://example.com',
        ]);

    $response->assertRedirect(route('subject.show', $subject->id, absolute: false));
    $this->assertDatabaseHas('subject_links', [
        'subject_id' => $subject->id,
        'title' => 'Home',
        'url' => 'https://example.com',
    ]);
});
