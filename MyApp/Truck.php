<?php

namespace MyApp;

class Truck
{
    public float $length;
    public float $width;
    public float $height;
    public float $max_load;
    public array $cargo = [];

    public function __construct(float $length, float $width, float $height, float $max_load)
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->max_load = $max_load;
    }

    public function addCargo(Cargo $cargo): void
    {
        $this->cargo[] = $cargo;
    }

    public function sortByWeight(): void
    {
        usort($this->cargo, function ($a, $b) {
            return $b->weight - $a->weight;
        });
    }

    public function distributeCargo(): array
    {
        $load_weight = 0;
        $x = 0; // координата X верхнего левого угла текущего груза
        $y = 0; // координата Y верхнего левого угла текущего груза
        $z = 0; // координата Z верхнего левого угла текущего груза
        $unloaded_cargo = [];

        foreach ($this->cargo as $c) {
            if (($load_weight + $c->weight) <= $this->max_load) { // если груз можно загрузить
                // проверка, поместится ли груз по длине в оставшееся пространство
                if (($x + $c->length + 0.05) > $this->length) { // если не поместится
                    $x = 0;
                    $y += $z + 0.05;
                    $z = 0;
                }
                // проверка, поместится ли груз по ширине в оставшееся пространство
                if (($y + $c->width + 0.05) > $this->width) { // если не поместится
                    $unloaded_cargo[] = $c;
                    continue; // переходим к следующему грузу
                }
                // проверка, поместится ли груз по высоте в оставшееся пространство
                if (($z + $c->height + 0.05) > $this->height) { // если не поместится
                    $unloaded_cargo[] = $c;
                    continue; // переходим к следующемгрузу
                }
                // загрузка груза
                $x += 0.05;
                $load_weight += $c->weight;
                $c->x = $x;
                $c->y = $y;
                $c->z = $z;
                $z += $c->height + 0.05;
            } else { // если груз нельзя загрузить
                $unloaded_cargo[] = $c;
            }
        }
        return $unloaded_cargo;
    }

    public function drawCargoLayout(): string
    {
        $width = $this->width * 20;
        $length = $this->length * 20;

        // Создание нового изображения
        $image = imagecreatetruecolor($width, $length);

        // Задание цветов
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 192, 192, 192);
        $red = imagecolorallocate($image, 255, 0, 0);

        // Заливка фона
        imagefill($image, 0, 0, $white);

        // Кузов машины
        imagefilledrectangle($image, 0, 0, $width, $length, $gray);

        // Грузы
        foreach ($this->cargo as $c) {
            $left = $c->x * 20;
            $top = $c->y * 20;
            $cargo_width = $c->length * 20;
            $cargo_height = $c->width * 20;

            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($image, $left, $top, $left + $cargo_width, $top + $cargo_height, $color);

            // Название груза
            $font_size = 10;
            $font_file = 'path/to/font.ttf';
            $text_color = $black;
            $text_width = $cargo_width - 10;
            $text_height = $cargo_height - 10;
            $angle = 0;
            $x = $left + ($cargo_width - $text_width) / 2;
            $y = $top + ($cargo_height - $text_height) / 2 + $font_size;
            imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font_file, $c->name);
        }

        // Сохранение изображения в файл
        $image_path = 'image.png';
        imagepng($image, $image_path);
        imagedestroy($image);

        // Формирование ссылки на изображение
        $html = '<img src="'.$image_path.'" alt="Cargo Layout" style="max-width: 100%; height: auto;">';

        return $html;
    }
}