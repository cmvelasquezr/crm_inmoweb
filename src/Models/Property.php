<?php
namespace Src\Models;

class Property {
    public $id;
    public $title;
    public $zone;
    public $price;
    public $rooms;
    public $hasGarage;
    public $hasTerrace;
    public $areaM2;

    public function __construct($id, $title, $zone, $price, $rooms, $hasGarage, $hasTerrace, $areaM2) {
        $this->id = $id;
        $this->title = $title;
        $this->zone = $zone;
        $this->price = $price;
        $this->rooms = $rooms;
        $this->hasGarage = $hasGarage;
        $this->hasTerrace = $hasTerrace;
        $this->areaM2 = $areaM2;
    }
}
