<?php
/**
 * 部分代码来自Yii 2
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 *
 */
namespace DG24\DI;

/**
 * ServiceLocator是一个在DI（依赖注入）中，可以任意定义实例属性的类
 * 你可以看作是一个可以装载任意别名实例的类容器
 *
 * 此类可被任意类继承，或者单独作为一个容器实例化
 *
 * @link http://en.wikipedia.org/wiki/Service_locator_pattern
 * @author Horse Luke
 *
 */
class ServiceLocator{

    /**
     * 注册的实例
     * @var array
     */
    private $___aliasInstance = array();

    /**
     * 注册的定义
     * @var array
     */
    private $___aliasDefinition = array();

    /**
     * 实例化
     * @param array $aliasBatch
     */
    public function __construct(array $aliasBatch = array()){
        if(!empty($aliasBatch)){
            $this->regBatch($aliasBatch);
        }
    }

    /**
     * 注册一个别名实例
     * @param string $name 别名实例名称
     * @param string|object|function $code 代码，可以为如下内容：
     *     null或空字符串：将$name从该容器中删除
     *     字符串：一个无构造参数类名称
     *     已经实例化的对象：直接注册一个实例
     *     匿名函数：一个返回实例的匿名函数
     * @return bool
     * @throws \InvalidArgumentExceptioneption
     */
    public function reg($name, $code = null){

        if(empty($code)){
            unset($this->___aliasDefinition[$name], $this->___aliasInstance[$name]);
            return false;
        }

        if(is_string($code) || is_object($code)){
            $this->___aliasDefinition[$name] = $code;
            return true;
        }

        throw new \InvalidArgumentException('Code for alias name '. $name. ' does not match requirement');

    }

    /**
     * 批量注册别名实例
     * @param array $name 一个数组。
     * key为$name(别名实例名称)；
     * value见{@link DG24\DI\ServiceLocator::reg()}的第二个参数
     * @return boolean
     */
    public function regBatch($name){
        foreach($name as $k => $v){
            $this->reg($k, $v);
        }
        return true;
    }

    /**
     * 获取一个别名实例的单例
     * @param string $name
     * @throws \InvalidArgumentException
     * @return object
     */
    public function get($name){
        if(isset($this->___aliasInstance[$name])){
            return $this->___aliasInstance[$name];
        }

        $this->___aliasInstance[$name] = $this->create($name, false);
        return $this->___aliasInstance[$name];

    }

    /**
     * 新建一个别名实例
     * 注意：如果之前reg的是一个实例，那么会受$objectClone的影响
     * @param string $name 别名实例名称
     * @param boolean $objectClone 如果reg的是一个实例，那么是否clone？默认为true，即深度copy一个对象
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return object
     */
    public function create($name, $objectClone = true){
        if(!isset($this->___aliasDefinition[$name])){
            throw new \InvalidArgumentException('Can not find alias name '. $name);
        }

        $code = $this->___aliasDefinition[$name];

        if(is_string($code)){
            return new $code();
        }

        if($code instanceof \Closure){
            $instance = $code($this);

            if(!is_object($instance)){
                throw new \RuntimeException('Can not create instance for alias name '. $name);
            }

            return $instance;

        }

        //reg已经过过滤，若上面逻辑不通过，剩下的只可能是object了
        if(!$objectClone){
            return $code;
        }else{
            return clone $code;
        }
    }

    /**
     * （魔术方法）获取一个别名实例
     * @param string $name 别名实例名称
     * @return array
     */
    public function __get($name){
        return $this->get($name);
    }

    /**
     * （魔术方法）是否存在该别名实例
     * @param string $name
     * @return bool
     */
    public function __isset($name){
        return $this->has($name);
    }

    /**
     * 是否存在该别名实例
     * @param string $name 别名实例名称
     * @param boolean $checkInstance 是否已经实例化（即调用过get）？默认仅检查是否reg。
     * @return boolean
     */
    public function has($name, $checkInstance = false){
        return $checkInstance ? isset($this->___aliasInstance[$name]) : isset($this->___aliasDefinition[$name]);
    }

}