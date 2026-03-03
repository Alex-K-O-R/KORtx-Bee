<?php


namespace app\nodes;


use app\dba\FileDBA;

class NodeManager
{
    private static $CONFIG_FILE = 'nodes_config.json';
    private static $NODES_PATH = DIRECTORY_SEPARATOR.'nodes';

    private static $CONFIG_NODES = array(
        'last_build' => null,
        'discovered' => [],
        'js' => [],
        'php' => [],
        'css' => [],
    );

    private static function saveConfig() {
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.self::$CONFIG_FILE, json_encode(self::$CONFIG_NODES));
    }

    private static function loadConfig() {
        $config = __DIR__.DIRECTORY_SEPARATOR.self::$CONFIG_FILE;
        $config = is_readable($config) ?
            json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.self::$CONFIG_FILE), true) : null;
        self::$CONFIG_NODES = $config ?: self::$CONFIG_NODES;
    }

    public static function loadNodes() {
        $freshConfigImg = self::$CONFIG_NODES;
        $nodesPath = DOCUMENT_ROOT.self::$NODES_PATH;

        FileDBA::traverseDirectory(
            $nodesPath,
            function (string $diskNode) use (&$freshConfigImg) {
                if (is_dir($diskNode)) {
                    $possibleNode = $diskNode.DIRECTORY_SEPARATOR.basename($diskNode).'.php';

                    if (file_exists($possibleNode)) {
                        $isOnCheck = [];
                        $isOn = preg_match('/(\$IS_ENABLED)[ ]*=[ ]*([A-Za-z]+);/m', file_get_contents($possibleNode), $isOnCheck);
                        if (!$isOn || $isOn && mb_strtolower($isOnCheck[2] ?? '') === 'true') {
                            $freshConfigImg['discovered'][] = $diskNode;
                        }
                    }
                }
            }
        );

        self::loadConfig();
        if (empty(self::$CONFIG_NODES['last_build']) || $freshConfigImg['discovered'] != self::$CONFIG_NODES['discovered']) {
            self::scanFiles($freshConfigImg);
            $freshConfigImg['last_build'] = time();
            self::$CONFIG_NODES = $freshConfigImg;
            self::saveConfig();
        }
    }

    private static function scanFiles(array &$configImg) {
        foreach ($configImg['discovered'] as $dir) {
            $configImg['php'] = array_merge($configImg['php'], self::scanFilesOfType($dir, '*.php'));
            usort($configImg['php'], function ($node) {
                return \app\utilities\inner\Strings::startsWith(
                    $node, DOCUMENT_ROOT.self::$NODES_PATH.DIRECTORY_SEPARATOR.'original'
                ) ? -1 : 1;
            });
            $configImg['js'] = array_merge($configImg['js'], self::scanFilesOfType($dir, '*.js'));
            $configImg['css'] = array_merge($configImg['css'], self::scanFilesOfType($dir, '*.css'));
        }
    }

    private static function scanFilesOfType(string $dir, string $globType) {
        $results = array();
        if ($handle = opendir($dir)) {
            while (false !== ($filename = readdir($handle))) {
                 if (fnmatch($globType, $filename, FNM_NOESCAPE))
                    $results[] = $dir.DIRECTORY_SEPARATOR.$filename;
            }
        }
        closedir($handle);

        return $results;
    }

    /**
     * @param array $configImg
     * @return int|string|null
     */
    private static function getMaxChangesDateOfFiles(array $configImg)
    {
        $files = array_merge($configImg['php'], $configImg['js'], $configImg['css']);
        $files = array_combine($files, array_map("filemtime", $files));
        arsort($files);

        return current($files);
    }

    public static function getPhps() {
        return self::$CONFIG_NODES['php'];
    }

    public static function getJs() {
        return array_map(function ($path) {return str_replace([DOCUMENT_ROOT, DIRECTORY_SEPARATOR], ['', '/'], $path);}, self::$CONFIG_NODES['js']);
    }

    public static function getCss() {
        return array_map(function ($path) {return str_replace([DOCUMENT_ROOT, DIRECTORY_SEPARATOR], ['', '/'], $path);}, self::$CONFIG_NODES['css']);
    }
}