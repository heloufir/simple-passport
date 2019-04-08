<?php

namespace Heloufir\SimplePassport;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'simple_tokens';

    protected $fillable = [
        'token'
    ];
}
