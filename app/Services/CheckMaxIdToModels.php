<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class CheckMaxIdToModels
{
    /**
     * @var Model[]
     */
    private $models = [];

    /**
     * CheckMaxIdToModels constructor.
     * @param Model[] $models
     */
    public function __construct(\IteratorAggregate $models)
    {
        $this->models = $models;
    }

    public function getMaxIds()
    {
        $res = [];
        /** @var Model $model */
        foreach ($this->models as $model) {
            $res[$model->getTable()] = $this->getMaxId($model);
        }

        return $res;
    }

    private function getMaxId(Model $model): string
    {
        $pk = $model->getKeyName();

        return $model::query()->max($pk);
    }

}
