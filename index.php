<?php

// Пример использования классов:

use MyApp\Cargo;
use MyApp\Truck;

require_once 'MyApp/Cargo.php';
require_once 'MyApp/Truck.php';

$truck = new Truck(4, 2, 2, 500);

$cargo1 = new Cargo('Гр1', 2, 1.5, 1, 200);
$cargo2 = new Cargo('Гр2', 3, 1.5, 0.5, 150);
$cargo3 = new Cargo('Гр3', 1, 0.8, 0.4, 50);
$cargo4 = new Cargo('Гр4', 1.5, 0.8, 0.4, 80);

$truck->addCargo($cargo1);
$truck->addCargo($cargo2);
$truck->addCargo($cargo3);
$truck->addCargo($cargo4);

$truck->sortByWeight();

$unloaded_cargo = $truck->distributeCargo();

$html = '';

foreach ($unloaded_cargo as $c) {
    $html .= 'Груз ' . $c->name . ' не может быть загружен в силу ограничений кузова.' . "<br>";
}

$html .= $truck->drawCargoLayout();

echo $html;
