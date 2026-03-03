<?php

namespace common\helpers;

class ColorHelper
{
    /**
     * @param string $seed
     * @param int $alpha
     * @return string
     */
    public static function generateSeedRgbaColor($seed = 'default', $alpha = 1, $boost = 1)
    {
        srand((intval($seed,36) * $boost));
        $red = static::randomRgbColorPart();
        srand((intval($seed,26) * $boost));
        $green = static::randomRgbColorPart();
        srand((intval($seed,16) * $boost));
        $blue = static::randomRgbColorPart();
        return "rgba({$red}, {$green}, {$blue}, $alpha)";
    }

    /**
     * @return int
     */
    public static function randomRgbColorPart()
    {
        return mt_rand(0, 255);
    }
}