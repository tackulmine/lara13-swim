<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MyLaraCedObserver
{
    /**
     * Get User id.
     *
     * @return int
     */
    protected function getUserId()
    {
        return auth()->id() ?? 1;
    }

    public function creating(Model $model)
    {
        $model->{$model->getCreatorColumn()}
        = $this->getUserId();
    }

    public function updating(Model $model)
    {
        if ($model->isDirty($model->{$model->getDestroyerColumn()})) {
            // do nothing
        } elseif ($model->isDirty()) {
            $model->{$model->getEditorColumn()}
            = $this->getUserId();
        }
    }

    public function deleting(Model $model)
    {
        if (! Schema::hasColumn($model->getTable(), $model->getDestroyerColumn())) {
            return;
        }

        $model->{$model->getDestroyerColumn()}
        = $this->getUserId();
        $model->save();
    }
}
