<?php

namespace DG24\StdTrait;


use DG24\Error\CommonError;

/**
 * 类错误属性。用于返回标准错误值
 *
 *
 */
trait PropertyLastErrorStdTrait{

    /**
     * 最后的错误
     * @var null|string
     */
    protected $__lastErrorMsgStdTrait = null;

    /**
     * 最后的错误代码
     * @var integer
     */
    protected $__lastErrorCodeStdTrait = 0;

    /**
     * 最后的错误额外信息
     * @var mixed
     */
    protected $__lastErrorExtraStdTrait = null;

    /**
     * 是否隐藏最后的错误额外信息
     * @var bool
     */
    protected $__lastErrorHideExtraStdTrait = true;

    /**
     * 获取最后的错误代码
     * @return integer
     */
    public function getLastErrorCode(){
        return $this->__lastErrorCodeStdTrait;
    }

    /**
     * 获取最后的错误信息
     * @return NULL|string
     */
    public function getLastErrorMsg(){
        return $this->__lastErrorMsgStdTrait;
    }

    /**
     * 获取最后的错误额外信息
     * @return null|mixed
     */
    public function getLastErrorExtra(){
        if($this->__lastErrorHideExtraStdTrait !== false){
            return null;
        }
        return $this->__lastErrorExtraStdTrait;
    }

    /**
     * 获取配置：是否隐藏最后的错误额外信息
     * @return bool
     */
    public function getLastErrorHideExtra(){
        return $this->__lastErrorHideExtraStdTrait;
    }

    /**
     * 设置配置：是否隐藏最后的错误额外信息
     * @param boolean $hideExtra
     * @return bool
     */
    public function setLastErrorHideExtra($hideExtra = true){
        $this->__lastErrorHideExtraStdTrait = $hideExtra;
        return $hideExtra;
    }

    /**
     * 获取最后的错误集合信息
     * @param null|bool $hideExtra 是否同时获取最后的错误额外信息？null表示跟随原有设置。true/false将暂时强制设置
     * @return array
     */
    public function getLastErrorSummary($hideExtra = null){
        if($hideExtra === null){
            $hideExtra = $this->__lastErrorHideExtraStdTrait;
        }
        return array(
            'error_msg' => $this->__lastErrorMsgStdTrait,
            'error_code' => $this->__lastErrorCodeStdTrait,
            'error_extra' => $hideExtra !== false ? null : $this->__lastErrorExtraStdTrait,
        );
    }

    /**
     * 设置错误
     * @param string $msg
     * @param int $code
     * @param mixed $extra
     * @return int
     */
    public function setLastError($msg = "DEFAULT_LAST_ERROR", $code = -1, $extra = null){
        $this->__lastErrorCodeStdTrait = $code;
        $this->__lastErrorMsgStdTrait = $msg;
        $this->__lastErrorExtraStdTrait = $extra;
        return $code;
    }

    /**
     * 将指定实例的最后一个错误注入覆盖到本实例中
     * @param PropertyLastErrorStdTrait $object
     * @return int
     */
    public function setLastErrorOverwrite($object){
        if(!method_exists($object, 'setLastErrorHideExtra')){
            throw new \InvalidArgumentException(get_class($object). " DOES NOT HAVE TRAIT PropertyLastErrorStdTrait!");
        }

        $error_summary = $object->getLastErrorSummary(false);
        $this->__lastErrorCodeStdTrait = $error_summary['error_code'];
        $this->__lastErrorMsgStdTrait = $error_summary['error_msg'];
        $this->__lastErrorExtraStdTrait = $error_summary['error_extra'];
        return $error_summary['error_code'];
    }

    /**
     * @deprecated
     * @param PropertyLastErrorStdTrait $object
     * @return int
     */
    public function overWriteSetLastError($object){
        return $this->setLastErrorOverwrite($object);
    }


    public function setLastErrorFromCommonError(CommonError $err){
        $this->__lastErrorCodeStdTrait = $err->code;
        $this->__lastErrorMsgStdTrait = $err->error;
        $this->__lastErrorExtraStdTrait = $err;
        return $err->code;
    }

    /**
     * 清空错误
     */
    public function clearLastError(){
        $this->__lastErrorCodeStdTrait = 0;
        $this->__lastErrorMsgStdTrait = null;
        $this->__lastErrorExtraStdTrait = null;
        $this->__lastErrorHideExtraStdTrait = true;
    }

}