<?php

namespace App\Http\Controllers;

use App\City;
use App\Services\GetFullNameStreet;
use App\StreetProviderInterface;

class AddressController extends Controller
{
    /**
     * @var StreetProviderInterface
     */
    private $street;

    /**
     * @var City
     */
    private $city;

    private $getFullNameStreet;

    /**
     * AddressController constructor.
     * @param StreetProviderInterface $street
     * @param City $city
     */
    public function __construct(StreetProviderInterface $street, City $city, GetFullNameStreet $getFullNameStreet)
    {
        $this->street = $street;
        $this->city = $city;
        $this->getFullNameStreet = $getFullNameStreet;
    }

    public function getListStreets()
    {
        $streets = $this->street->getAll();

        return view('address/list_streets', ['streets' => $streets]);
    }

    public function getStreetByName($name)
    {
        $fullName = $this->getFullNameStreet->getByName($name);

        echo  $fullName . '<br>';
    }

    public function getCityByName($name)
    {
        $city = $this->city->getQB()->where(['name' => $name])->first();
        echo  $city->name . ' - <br>';
        $streets = $city->streets;

        foreach ($streets as $street) {
            echo '   ' . $street->type .'. ' . $street->name . '<br>';
        }
    }
}
