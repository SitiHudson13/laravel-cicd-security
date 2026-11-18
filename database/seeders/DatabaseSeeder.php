<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Deterministic training data. Project IDs are fixed so instructions can reference them:
 *   Alpha Sdn Bhd -> projects 1, 2, 3
 *   Beta  Sdn Bhd -> projects 4, 5, 6   (project 4 carries a confidential marker)
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('LAB_PASSWORD', 'Lab-Passw0rd!');

        $alpha = Organization::create(['name' => 'Alpha Sdn Bhd']);
        $beta = Organization::create(['name' => 'Beta Sdn Bhd']);

        $users = [
            ['Alpha Admin', 'admin.alpha@example.test', $alpha, 'admin'],
            ['Alpha Staff', 'staff.alpha@example.test', $alpha, 'member'],
            ['Beta Admin', 'admin.beta@example.test', $beta, 'admin'],
            ['Beta Staff', 'staff.beta@example.test', $beta, 'member'],
        ];

        $created = [];
        foreach ($users as [$name, $email, $org, $role]) {
            $created[$email] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'organization_id' => $org->id,
                'role' => $role,
            ]);
        }

        $projects = [
            [$alpha, 'admin.alpha@example.test', 'Alpha Website Redesign', 'Public-facing website refresh for Alpha.'],
            [$alpha, 'staff.alpha@example.test', 'Alpha Mobile App', 'Customer app MVP. Internal notes only.'],
            [$alpha, 'admin.alpha@example.test', 'Alpha Q4 Budget', 'Budget planning for Alpha Q4.'],
            [$beta, 'admin.beta@example.test', 'Beta Acquisition Plan', 'BETA-PRIVATE-MARKER: confidential acquisition strategy.'],
            [$beta, 'staff.beta@example.test', 'Beta Payroll Migration', 'Payroll system migration plan for Beta.'],
            [$beta, 'admin.beta@example.test', 'Beta Security Audit', 'Findings from the Beta security audit.'],
        ];

        foreach ($projects as [$org, $creatorEmail, $name, $description]) {
            $project = Project::create([
                'organization_id' => $org->id,
                'name' => $name,
                'description' => $description,
                'created_by' => $created[$creatorEmail]->id,
            ]);

            $this->createDocument($project, $created[$creatorEmail], $org->name);
        }
    }

    private function createDocument(Project $project, User $uploader, string $orgName): void
    {
        $filename = 'project-'.$project->id.'-notes.txt';
        $path = "documents/{$project->id}/{$filename}";
        $contents = "Document for project #{$project->id} ({$project->name}) owned by {$orgName}.\n"
            .($project->id === 4 ? "BETA-CONFIDENTIAL-DOCUMENT: do not disclose outside Beta Sdn Bhd.\n" : '');

        Storage::disk('local')->put($path, $contents);

        Document::create([
            'project_id' => $project->id,
            'original_name' => $filename,
            'path' => $path,
            'mime_type' => 'text/plain',
            'size' => strlen($contents),
            'uploaded_by' => $uploader->id,
        ]);
    }
}
