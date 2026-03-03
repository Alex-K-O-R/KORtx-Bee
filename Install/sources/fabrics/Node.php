<?php
namespace app\nodes;

use app\Application;

abstract class Node {
    public static $IS_ENABLED = true;
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @var Application $App
     */
    protected $App;

    private function __construct(Application $App){
        $this->App = $App;
    }

    protected abstract function Render(?NodeOptions $nodeParametersContainer = null);

    /**
     * @param Application $appContext
     * @param ?NodeOptions $parameters
     */
    public static function Draw(Application $appContext, NodeOptions $parameters): void
    {
        $cacheId = crc32(serialize($appContext)).($parameters ? crc32(serialize($parameters->getAllVars())) : '');
        if (!isset(static::$cache[$cacheId])) {
            $instance = new static($appContext);
            ob_start();
            $instance->Render($parameters);
            static::$cache[$cacheId] = ob_get_clean();
        }

        echo static::$cache[$cacheId];
    }
}