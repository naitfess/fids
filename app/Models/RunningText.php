<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    protected $table = 'table_running_text';

    protected $fillable = [
        'text'
    ];
}
