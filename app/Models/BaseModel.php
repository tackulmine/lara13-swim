<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    protected $connection = 'mysql';

    public static function table()
    {
        $instance = new static;

        return $instance->getTable();
    }
}
