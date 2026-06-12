<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GedIndex extends Model
{
    protected $table = 'ged_index';

    protected $fillable = [
        'tipo',
        'path',
        'nome',
        'is_file',
        'parent_path',
    ];
}
