<?php

use App\Models\User;
use App\Models\Subject;

it('can assign a subject to another subject', function () {
    $user = User::factory()->create();
    $parent = Subject::factory()->create(['name' => 'Parent']);
    $child = Subject::factory()->create(['name' => 'Child']);

    $parent->children()->attach($child->id);

    $this->assertDatabaseHas('subject_relations', [
        'parent_id' => $parent->id,
        'child_id' => $child->id,
    ]);
});
