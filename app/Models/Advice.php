<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advice extends Model
{
    protected $table = 'advice';

    protected $fillable = ['name','description'];
}
