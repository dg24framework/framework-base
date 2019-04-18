<?php
namespace DG24;

class Loader {

    const VERSION = '1.3.19041801';

    /**
     * 全局应用
     * @var \DG24\SolutionBare\App
     */
    public static $app;

    protected static $loadClassPath = array();

    protected static $conf;

    /**
     * 历史遗留计数器，已不使用
     * @deprecated
     * @var int
     */
    public static $counter = 0;

    public static $starttime = 0;

    const AUTOLOAD_DISABLE_REGLOADER = -1;

    const AUTOLOAD_DISABLE = 0;

    const AUTOLOAD_ONLY_APP = 3;

    const AUTOLOAD_ONLY_FRAMEWORK = 1;

    const AUTOLOAD_ALL = 4;

    /**
     * 以PSR-4标准载入类
     * @param string $className
     */
    public static function loadClass($className) {

        $className = str_replace('\\', '/', $className);
        $className = ltrim($className, '/'). '.php';

        foreach(self::$loadClassPath as $findpath){
            $realFilepath = $findpath. DIRECTORY_SEPARATOR. $className;
            if(self::file_exists_case($realFilepath)){
                require $realFilepath;
                return ;
            }
        }

    }

    /**
     * 定义并初始化整个框架
     * @param array $define 框架环境配置定义。
     * 必填选项有：
     *     string D_APP_DIR：应用目录（即app目录）路径。
     *     string D_ENTRY_FILE：脚本访问入口路径
     * 可选值有：
     *     string D_ENV：配置环境，默认为Prod
     *     int D_DEBUG：开启debug？默认为0
     *     string D_CONTROLLER_NAME：控制器前缀，默认为Controller
     *     bool D_COMPAT_CLASS_FIX：为兼容1.0以下框架而设，用于修正0.x版本框架项目移植到1.x版本框架时出现的类名不兼容问题。新项目勿使用本定义！
     * @param bool $autoloadFramework 自动载入框架的模式。注意参数不能进行与或运算。
     *     Loader::AUTOLOAD_ONLY_APP：（默认）仅自动载入APP目录，框架部分交由外部载入（如composer）。
     *     Loader::AUTOLOAD_ONLY_FRAMEWORK：仅自动载入框架部分，APP目录交由外部载入
     *     Loader::AUTOLOAD_ALL：由本loader同时自动载入框架和APP目录
     *     Loader::AUTOLOAD_DISABLE：仍然向php注册框架自动载入机制，但禁止所有自动载入，不作任何处理，由用户自行处理或交由外部载入（如composer）
     *     Loader::AUTOLOAD_DISABLE_REGLOADER：禁止本框架向php注册框架自动载入机制，同时禁止所有自动载入，不作任何处理，由用户自行处理或交由外部载入（如composer）
     * @return void 定义完成后，整个框架将被初始化。产生如下结果：
     *     （1）定义D_CONTROLLER_NAME（若无定义）
     *     （2）spl_autoload_register该类的loadClass
     */
    public static function define($define, $autoloadFramework = self::AUTOLOAD_ONLY_APP){

        self::$starttime = microtime(true);

        foreach($define as $k => $v){
            define($k, $v);
        }

        // 务必定义应用目录路径
        if(!defined('D_APP_DIR') || !defined('D_ENTRY_FILE')){
            throw new \InvalidArgumentException('D_APP_DIR OR D_ENTRY_FILE NOT PASSED!');
        }

        if(!defined('D_ENV')){
            define('D_ENV', 'Prod');
        }

        if(!defined('D_DEBUG')){
            define('D_DEBUG', 0);
        }

        if(!defined('D_CONTROLLER_NAME')){
            define('D_CONTROLLER_NAME', 'Controller');
        }

        if (D_DEBUG) {
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }

        if($autoloadFramework > 0){
            if(self::AUTOLOAD_ALL == $autoloadFramework){
                self::$loadClassPath['framework'] = dirname(__DIR__);
                self::$loadClassPath['app'] = D_APP_DIR. DIRECTORY_SEPARATOR. 'Class';
            }elseif(self::AUTOLOAD_ONLY_APP == $autoloadFramework){
                self::$loadClassPath['app'] = D_APP_DIR. DIRECTORY_SEPARATOR. 'Class';
            }elseif(self::AUTOLOAD_ONLY_FRAMEWORK == $autoloadFramework){
                self::$loadClassPath['framework'] = dirname(__DIR__);
            }
        }

        if($autoloadFramework != self::AUTOLOAD_DISABLE_REGLOADER){
            spl_autoload_register(array('self', 'loadClass'));
        }
    }

    /**
     * 获取所有自动载入的路径
     * @return array
     */
    public static function getLoaderPath(){
        return self::$loadClassPath;
    }

    /**
     * 增加或设置载入路径
     * @param string $name 载入路径别名
     * @param string $path 载入路径
     * @param string $order 载入顺序。
     *     default：如果$name不存在，则放到最后；如果$name存在，则保持顺序不变，只更改为新的$path值
     *     first：放到载入顺序最前面
     *     last：放到载入顺序最后面
     */
    public static function setLoaderPath($name, $path, $order = 'default'){

        if('default' == $order){
            self::$loadClassPath[$name] = $path;
            return true;
        }

        if(isset(self::$loadClassPath[$name])){
            unset(self::$loadClassPath[$name]);
        }

        if(!in_array($order, array('last', 'first'))){
            $order = 'last';
        }

        if('last' == $order){
            self::$loadClassPath[$name] = $path;
        }else{
            $new = array($name => $path);
            foreach(self::$loadClassPath as $k => $v){
                $new[$k] = $v;
            }
            self::$loadClassPath = $new;
        }

        return true;
    }

    /**
     * 删除某个载入路径
     * @param string $name 载入路径别名
     * @return bool 删除成功则为true，否则为false
     */
    public static function delLoaderPath($name){
        if(!isset(self::$loadClassPath[$name])){
            return false;
        }

        unset(self::$loadClassPath[$name]);
        return true;

    }

    /**
     * 获取一个配置。依次读取路径请参考{@link DG24\Loader\configRead()}
     * @param string $source 源。注意：此处不检查其路径！
     * @param string $name 可以用>获取下一个数组内的name，比如"default>0"，表示获取$conf['default'][0]内的内容
     * @param string $default
     * @return mixed
     */
    public static function config($source, $key = null, $default = null){
        if(!isset(self::$conf[$source])){
            self::$conf[$source] = self::configRead($source);
        }

        if(empty($key)){
            return self::$conf[$source];
        }elseif(strpos($key, '>') !== false){
            $key = explode('>', $key);
            $val = self::$conf[$source];
            foreach($key as $k){
                if(is_array($val) && isset($val[$k])){
                    $val = $val[$k];
                }else{
                    $val = $default;
                    break;
                }
            }

            return $val;
        }else{
            return isset(self::$conf[$source][$key]) ? self::$conf[$source][$key] : $default;
        }

    }

    /**
     * 从指定源中读取配置，依次读取路径：
     *     D_APP_DIR/Config/Default目录下的指定文件
     *     D_APP_DIR/Config/D_ENV目录下的指定文件
     * @param string $source 源。注意：此处不检查其路径！
     * @return array
     */
    public static function configRead($source){
        $conf = array();

        $sourceFiles = array(
            D_APP_DIR. '/Config/Default/'. $source. '.php',
            D_APP_DIR. '/Config/'. D_ENV. '/'. $source. '.php',
        );

        foreach($sourceFiles as $file){

            if(!file_exists($file)){
                continue;
            }

            $newConf = require $file;
            if(is_array($newConf) && !empty($newConf)){
                $conf = self::arrayMerge($conf, $newConf);
            }elseif(is_callable($newConf, true)){
                $conf = $newConf($conf);
            }
        }

        return $conf;
    }

    /**
     * 两个数组合并，代码来自yiiframework
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will
     * be appended to the former array.
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional
     * arrays via third argument, fourth argument etc.
     * @return array the merged array (the original arrays are not changed.)
     */
    public static function arrayMerge($a, $b){
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_integer($k)) {
                    isset($res[$k]) ? $res[] = $v : $res[$k] = $v;
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::arrayMerge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }


    /**
     * 设置一组配置，将覆盖原值
     * @param string $source 源。若$reserveOld为false，则需要曾经Config::get()
     * @param string $key 类型
     * @param mixed $val 新的配置
     */
    public static function configSet($source, $key, $val = null){
        if(is_array($key)){
            foreach($key as $subkey => $v){
                self::$conf[$source][$subkey] = $v;
            }
        }else{
            self::$conf[$source][$key] = $val;
        }
    }

    /**
     * 清空所有Config
     */
    public static function configClear(){
        self::$conf = array();
    }

    /**
     * （快捷方法）获取某类的单例
     * @param string $name
     * @return object Object of class $name
     */
    public static function getInstance($name){
        return self::$app->getInstance($name);
    }

    /**
     * （快捷方法）获取某实例别称对象容器内指定名称的单例
     * @param string $name
     * @return object
     */
    public static function getAliasInstance($name){
        return self::$app->aliasInstance->get($name);
    }

    /**
     * 严格按照大小写，判断本地文件是否存在
     * 部分代码来自ThinkPHP，并进行适当裁剪
     * @param string $file
     * @return bool
     */
    public static function file_exists_case($filename){
        static $iswin = null;
        if(null === $iswin){
            $iswin = 0 === stripos(PHP_OS, 'win');
        }
        if (file_exists($filename)) {
            if ($iswin && D_DEBUG){
                if (basename(realpath($filename)) != basename($filename)){
                    $realpath = realpath($filename);
                    $errorStr = 'File_name_case_sensitive_error_on_linux_emulator_for_win!';
                    $errorStr .= ' [PASS PARAM FILE basename] '. basename($filename) . ' != [REAL FILE basename] '. basename($realpath);
                    $errorStr .= ' [PASS PARAM FILE] '. $filename. ' ;[REAL FILE]'. $realpath;
                    throw new \InvalidArgumentException($errorStr);
                    return false;
                }
            }
            return true;
        }
        return false;
    }

}