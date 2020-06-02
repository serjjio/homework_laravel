<?php

namespace App\Services;

use App\Street;
use App\StreetProviderInterface;

class GetFullNameStreet
{
    /**
     * @var StreetProviderInterface
     */
    private $street;

    /**
     * GetFullNameStreet constructor.
     * @param StreetProviderInterface $street
     */
    public function __construct(StreetProviderInterface $street)
    {
        $this->street = $street;
    }

    public function getByName(string $name): ?string
    {
        $street = $this->street->getQB()->where(['name' => $name])->first();

        if (empty($street)) {
            return null;
        }

        return  $street->city->name .' - ' . $street->type .'. ' . $street->name ;
    }
}
