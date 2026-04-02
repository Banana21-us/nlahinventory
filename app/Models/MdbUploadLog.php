<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdbUploadLog extends Model
{
    protected $fillable = [
        'filename', 'uploaded_by', 'records_imported', 'records_skipped',
        'employees_unmatched', 'dates_processed', 'status', 'error_message',
    ];

    protected $casts = [
        'dates_processed' => 'array',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
