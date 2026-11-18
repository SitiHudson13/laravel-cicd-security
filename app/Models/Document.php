<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = ['project_id', 'original_name', 'path', 'mime_type', 'size', 'uploaded_by'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
