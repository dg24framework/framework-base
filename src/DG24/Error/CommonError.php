<?php

namespace DG24\Error;

class CommonError{

    /**
     * 错误信息（概要或文字），公开
     * @var string
     */
    public $error = "";
    
    /**
     * 供内部使用的错误提示，不公开
     * @var mixed
     */
    public $errorInternal = "";
    
    /**
     * 错误代码，公开。注意：错误时，必不等于0
     * @var int
     */
    public $code = 1;
    
    /**
     * 错误详情，默认不公开，受方法allowOutputDetail()调控。
     * @var mixed
     */
    public $detail = null;
    
    /**
     * 错误类型，公开
     * @var String
     */
    public $type = "CommonError";
    
    protected $allowOutputDetail = false;
    
    /**
     * @param string $error 供外部使用的错误提示
     * @param string $errorInternal 供内部使用的错误提示
     * @param number $code 注意：code务必大于0
     * @param string $detail
     */
    public function __construct($error = "", $errorInternal = "", $code = 1, $detail = null){
        $this->error = $error;
        $this->errorInternal = $errorInternal;
        $this->code = $code != 0 ? $code : 1;
        $this->detail = $detail;
    }
    
    public function allowOutputDetail($v = null){
        if(is_bool($v)){
            $this->allowOutputDetail = $v;
        }
        return $this->allowOutputDetail;
    }
    
    public function __toString(){
        return $this->error;
    }
    
}