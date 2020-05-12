<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

interface StreetProviderInterface extends DbProviderInterface
{
    /**
     * @param Street $model
     * @return mixed
     */
    public function saveData(Model $model);
}
