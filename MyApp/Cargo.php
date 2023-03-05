<?php

namespace MyApp;

class Cargo
{
    public string $name;
    public float $length;
    public float $width;
    public float $height;
    public float $weight;
    public float $x = 0; // координата X верхнего левого угла груза
    public float $y = 0; // координата Y верхнего левого угла груза
    public float $z = 0; // координата Z верхнего левого угла груза

    public function __construct(string $name, float $length, float $width, float $height, float $weight)
    {
        $this->name = $name;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->weight = $weight;
    }
}