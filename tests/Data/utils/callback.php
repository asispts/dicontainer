<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
function fnCallback(string $string, array $array) : array
{
    return [$string, $array];
}


class StaticClass
{
    public static function callback(string $string, array $array) : array
    {
        return ['StaticClass', $string, $array];
    }
}

class ExtendStaticClass extends StaticClass
{
    public static function callback(string $string, array $array) : array
    {
        return ['ExtendStaticClass', $string, $array];
    }
}

class InvokeClass
{
    public function callback(string $string, array $array) : array
    {
        return ['InvokeClass', $string, $array];
    }

    public function __invoke(string $string, array $array) : array
    {
        return ['InvokeClass::__invoke', $string, $array];
    }
}
