<?php
namespace DG24\SolutionBare;

use DG24\Loader;
use DG24\DI\ServiceLocator;


/**
 * DG24应用启动器(Bare模式)
 * @author Horse Luke
 */
class App{
    
    /**
     * 实例别称对象容器
     * @var ServiceLocator
     */
    public $aliasInstance;
    
    
    /**
     * 实例对象树
     * @var array
     */
    protected $instanceTree;
    
    /**
     * 初始化
     * @param array $cfg 基础配置（Base）临时覆盖值
     */
    public function __construct(array $cfg = null){
        $this->initConfigBase($cfg);
    }
    
    /**
     * 初始化基础配置（Base）
     * @param array $newConf 基础配置（Base）临时覆盖值
     */
    protected function initConfigBase($newConf){
        $conf = Loader::configRead('Base');
        if(is_array($newConf) && !empty($newConf)){
        	$conf = Loader::arrayMerge($conf, $newConf);
        }elseif(is_callable($newConf, true)){
            $conf = $newConf($conf);
        }
        Loader::configSet('Base', $conf);
        
        date_default_timezone_set($conf['timezone']);
        
        $this->aliasInstance = new ServiceLocator();
        if(!empty($conf['aliasInstance'])){
            $this->aliasInstance->regBatch($conf['aliasInstance']);
        }
        
    }
    
    /**
     * 获取一个无构造方法的单例。如需要使用构造方法的单例，请使用本例的instanceAlias！
     * @param string $name 名称。前后请勿添加“\”！为效率，此处不进行处理！
     * @return object Object of class $name
     */
    public function getInstance($name){
        if(isset($this->instanceTree[$name])){
            return $this->instanceTree[$name];
        }
        
        $instance = new $name();
        $this->instanceTree[$name] = $instance;
        return $instance;
    }
    
}