<?php
namespace Src\Models;

class Weigth {
    public $zone;
    public $price;
    public $rooms;
    public $garage;
    public $terrace;
    public $area;
    
    public function __construct(
            int $zone = 30,
            int $price = 30,
            int $rooms = 15,
            int $garage = 10,
            int $terrace = 10,
            int $area = 5
        ) {
        $this->zone = $zone;
        $this->price = $price;
        $this->rooms = $rooms;
        $this->garage = $garage;
        $this->terrace = $terrace;
        $this->area = $area;

    }
}
