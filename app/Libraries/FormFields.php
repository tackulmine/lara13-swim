<?php

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class FormFields
{
    /**
     * Initialize model variable.
     *
     * @var Model
     */
    protected $model;

    protected $pkName;

    /**
     * List of fields and default value for each field.
     *
     * @var array
     */
    protected $fieldList = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->pkName = $model->getKeyName();

        $fields = $model->getFillable();

        $fieldList = [];
        foreach ($fields as $field) {
            $fieldList[$field] = '';
        }
        $this->fieldList = $fieldList;
    }

    /**
     * Execute the job.
     *
     * @return object
     */
    public function generateForm()
    {
        $fields = $this->fieldList;
        $pkName = $this->pkName;

        if (! empty($this->model->$pkName)) {
            $fields = $this->fieldsFromModel($fields);
        }

        $data = new \stdClass;
        foreach ($fields as $fieldName => $fieldValue) {
            $data->$fieldName = old($fieldName, $fieldValue);
        }

        return $data;
    }

    /**
     * Return the field values from the model.
     *
     * @return array
     */
    protected function fieldsFromModel(array $fields)
    {
        $fieldNames = array_keys(Arr::except($fields, []));
        $pkName = $this->pkName;

        $fields = [$pkName => $this->model->$pkName];
        foreach ($fieldNames as $field) {
            $fields[$field] = $this->model->{$field};
        }

        return $fields;
    }
}
