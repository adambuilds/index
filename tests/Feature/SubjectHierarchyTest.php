<?php

use App\Models\User;
use App\Models\Subject;

it('can assign a subject to another subject', function () {
    $user = User::factory()->create();
    $parent = Subject::factory()->create(['name' => 'Parent']);

    $response = $this
        ->actingAs($user)
        ->post('/subject', [
            'name' => 'Child',
            'parent_subject_id' => $parent->id,
        ]);

    $child = Subject::where('name', 'Child')->first();
    $response->assertRedirect(route('subject.show', $child->id, absolute: false));
    $this->assertDatabaseHas('subject_relationships', [
        'subject_id' => $child->id,
        'related_subject_id' => $parent->id,
        'type' => 'belongs_to',
    ]);
});
