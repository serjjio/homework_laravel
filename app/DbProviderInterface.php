<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface DbProviderInterface
{
    public function getAll($columns = ['*']): Collection;

    public function getQB(): Builder;

    public function saveData(Model $model);

    public function updateData(Model $model);

    public function deleteData(Model $model);
}
