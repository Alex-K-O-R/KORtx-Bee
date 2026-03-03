<?php


namespace app\nodes\others\Warning;


use app\nodes\NodeOptions;

class WarningOptions extends NodeOptions
{
    public static function buildFromParameters(?array $headerLangsArr = null, ?array $textLangsArray = null): NodeOptions
    {
        $options = new static();
        $options->addVar('h', $headerLangsArr);
        $options->addVar('txt', $textLangsArray);

        return $options;
    }

    public function getHeaderArr(): ?array
    {
        return $this->getVar('h');
    }

    public function getTextArr(): ?array
    {
        return $this->getVar('txt');
    }
}