<?php

namespace DG24\StdTrait;

/**
 * 类配置属性。后续内部可以此为基准进行该类的配置操作。
 *
 * 要使用本trait，需要在类中定义以“opt2”为前缀的类配置属性。
 * 比如：定义“protected $opt2logDir”，即表示设置一个以“logDir”的类配置属性，且允许外部读写。
 * 如果不想被外部通过getOption获取，则需要定义为以“opt2_”为前缀的类配置属性。
 * 比如：定义“protected $opt2_logDir”，即表示设置一个以“_logDir”的类配置属性，只允许外部写，不允许外部读。
 *
 * @author yaoying
 *
 */
trait PropertyOptionStdTrait{

  /**
   * 批量或单个设置类的属性配置（Option）
   * @param mixed $key 如果是数组，则批量设置，此时不用传value
   * @param mixed $value
   */
  public function setOption($key, $value = null)
  {
    if(is_array($key)){
      foreach ($key as $k => $v) {
        $k = 'opt2' . $k;
        if (!property_exists($this, $k)) {
          continue;
        }
        $this->{$k} = $v;
      }
    }else{
      $k = 'opt2' . $key;
      if (property_exists($this, $k)) {
        $this->{$k} = $value;
      }
    }
  }

  /**
   * 获取配置
   *
   * @param string $k
   * @return mixed
   */
  public function getOption($k)
  {
    if($k{0} == '_'){
      return null;
    }
    $k = 'opt2' . $k;
    return property_exists($this, $k) ? $this->{$k} : null;
  }

}