<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Document> */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'original_name' => 'notes.txt',
            'path' => 'documents/factory/'.Str::uuid().'.txt',
            'mime_type' => 'text/plain',
            'size' => 10,
            'uploaded_by' => null,
        ];
    }
}
