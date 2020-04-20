<?php

namespace App\Http\Controllers;

use App\Services\CheckMaxIdToModels;

class MainController extends Controller
{
    /**
     * @var CheckMaxIdToModels
     */
    private $checkMaxIdToModels;

    /**
     * MainController constructor.
     * @param CheckMaxIdToModels $checkMaxIdToModels
     */
    public function __construct(CheckMaxIdToModels $checkMaxIdToModels)
    {
        $this->checkMaxIdToModels = $checkMaxIdToModels;
    }

    public function getList()
    {
        foreach ($this->checkMaxIdToModels->getMaxIds() as $table => $id) {
            echo $table .': ' . $id . '<br>';
        }
    }
}
