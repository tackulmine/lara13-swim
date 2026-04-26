<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateArrayKeys implements Rule
{
    protected $table;

    protected $column;

    public function __construct($table, $column = 'id')
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        if (! is_array($value)) {
            return false;
        }

        $keys = array_keys($value);

        // Cek berapa banyak key yang benar-benar ada di database
        $count = DB::table($this->table)
            ->whereIn($this->column, $keys)
            ->count();

        // Harus sama jumlahnya antara key yang dikirim dan yang ditemukan di DB
        return $count === count($keys);
    }

    public function message()
    {
        return 'Satu atau lebih ID :attribute tidak ditemukan di sistem.';
    }
}
