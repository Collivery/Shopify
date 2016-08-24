<?php
namespace App\Helper;

use Mds\ColliveryClient;

class Resolver
{
    private $colliveryClient;

    public function __construct()
    {
        $this->colliveryClient = app('soap');
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
            return array_search($town, $suburbs);
        }

        return false;
    }
}
