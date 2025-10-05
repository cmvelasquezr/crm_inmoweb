<?php
namespace Src\Models;

class Client {
    public $id;
    public $name;
    public $preferredZones;
    public $budgetMin;
    public $budgetMax;
    public $roomsMin;
    public $roomsMax;
    public $needsGarage;
    public $wantsTerrace;

    public function __construct($id, $name, $zones, $budgetMin, $budgetMax, $roomsMin, $roomsMax, $needsGarage, $wantsTerrace) {
        $this->id = $id;
        $this->name = $name;
        $this->preferredZones = $zones;
        $this->budgetMin = $budgetMin;
        $this->budgetMax = $budgetMax;
        $this->roomsMin = $roomsMin;
        $this->roomsMax = $roomsMax;
        $this->needsGarage = $needsGarage;
        $this->wantsTerrace = $wantsTerrace;
    }
}
