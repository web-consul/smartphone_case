<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\Uploadable;
class Attachment extends Model
{

    use HasFactory;
    use Uploadable;

    protected $fillable = ['name', 'path'];
}
