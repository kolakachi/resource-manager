<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceModel extends Model
{
    use HasFactory;

    protected $table = "resources";

    protected $casts = [
        'open_in_new_tab' => 'boolean'
    ];
}
