<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomUniqueRule implements Rule
{
    protected $table;

    protected $column;

    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column, $message)
    {
        $this->table = $table;
        $this->column = $column;
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ! DB::table($this->table)
            ->where($this->column, $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'The :attribute already exists.';
    }
}
