<?php

namespace App\Helper;

use App\Soap\ColliverySoap;

class Resolver
{
    private $colliveryClient;

    public function __construct(ColliverySoap $colliveryClient)
    {
        $this->colliveryClient = $colliveryClient;
    }

    public function getTownId($townName)
    {
        $towns = $this->colliveryClient->getTowns();
        if ($towns) {
            return array_search($townName, $towns);
        }

        return false;
    }

    public function getSuburbId($suburbName, $town)
    {
        if (!preg_match('|^\d+$|', $town)) {
            $town = $this->getTownId($town);
        }

        $suburbs = $this->colliveryClient->getSuburbs($town);
        if ($suburbs) {
            $result = array_search($suburbName, $suburbs);

            if ($result === false) {
                return false;
            }

            return $result;
        }

        return false;
    }
}
