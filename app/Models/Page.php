<?php

namespace App\Models;

use App\Enums\PageKey;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'key' => PageKey::class, // string <-> enum
            'content' => 'array',    // RichEditor::json() => TipTap JSON
        ];
    }
}