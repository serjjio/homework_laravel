<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Street extends Model implements StreetProviderInterface
{
    //

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getAll($columns = ['*']): Collection
    {
        return self::all($columns);
    }

    public function getQB(): Builder
    {
        return self::query();
    }

    public function updateData(Model $model)
    {
        $model->update();
    }

    public function deleteData(Model $model)
    {
        $model->delete();
    }

    public function saveData(Model $model)
    {
        $model->save();
    }
}
