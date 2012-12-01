<?php

namespace Emicro\Plugin;

class Assets
{
    protected static $production;
    protected static $js = array();
    protected static $css = array();

    public static function setProduction($val)
    {
        self::$production = $val;
    }

    public static function js($url)
    {
        self::$js[] = $url;
    }

    public static function css($url)
    {
        self::$css[] = $url;
    }

    public static function includeScripts()
    {
        $output = '';

        if (self::$production) {
            $output = '<script type="text/javascript" src="' . PRODUCTION_JS_URL . '?v=' . PRODUCTION_ASSETS_VERSION . '"></script>' . PHP_EOL;
        } else {
            foreach (self::$js as $js) {
                $output .= '<script type="text/javascript" src="' . $js . '?v=' . PRODUCTION_ASSETS_VERSION . '"></script>' . PHP_EOL;
            }
        }

        echo $output;
    }

    public static function includeStyles()
    {
        $output = '';
        if (self::$production) {
            $output .= '<link rel="stylesheet" href="' . PRODUCTION_CSS_URL . '?v=' . PRODUCTION_ASSETS_VERSION . '">' . PHP_EOL;
        } else {
            foreach (self::$css as $css) {
                $output .= '<link rel="stylesheet" href="' . $css . '?v=' . PRODUCTION_ASSETS_VERSION . '">' . PHP_EOL;
            }
        }

        echo $output;
    }
}