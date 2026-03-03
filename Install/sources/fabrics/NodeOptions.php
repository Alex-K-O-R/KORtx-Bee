<?php


namespace app\nodes;


use app\VariablesContainer;

abstract class NodeOptions
{
    use VariablesContainer;

    protected final function __construct() {}

    public static function buildFromParameters(): NodeOptions
    {
        return new static();
    }

    public function __get($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }

        throw new \Exception('Undefined property: '.__CLASS__.'::'.$name, U_UNDEFINED_VARIABLE);
    }

    public function getAllVars() {
        return $this->vars;
    }
}