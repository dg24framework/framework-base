<?php
/**
 * （默认）应用配置：基础
 */
$config = array(
	
    /**
     * DG24\Router\Simple参数
     * 从$_GET中指定的index取值，比如r表示从$_GET['r']中取值
     *
     */
    'routerSimpleFrom' => 'r',
    
    /**
     * DG24\Router\QueryString参数
     * 
     * SERVER：从$_SERVER中取值
     * GET：从$_GET中取值
     *
     */
    'routerQueryStringFrom' => 'SERVER',
    
    /**
     * DG24\Router\QueryString参数
     *
     * QUERY_STRING： QUERY_STRING（需要routerQueryStringFrom指定为SERVER）；
     * PATH_INFO：PATH_INFO（需要routerQueryStringFrom指定为SERVER）；
     * 其他：其它指定的index
     *
     */
    'routerQueryStringIndex' => 'QUERY_STRING',
    
    /**
     * 默认route，首字母无需大写，将自动转换
     */
    'defaultRoute' => 'index/index/index',
    
    /**
     * 时区
     */
    'timezone' => 'PRC',
    
    /**
     * hashSalt：用于部分简单验证的hashSalt
     */
    'hashSalt' => '',
    
    /**
     * 允许referer的domain。允许多个domain
     */
    'site_domain_allow_referer' => array(
        
    ),
    
    /**
     * cookie domain
     */
    'cookie_domain' => null,
    
    /**
     * cookie path
     */
    'cookie_path' => null,
    
    /**
     * 默认的模板id（前面无需加_View）。
     * 为空，则不指定
     */
    'viewTemplateId' => '',
    
    /**
     * 受信任的转发服务器ip（ipv4）
     * 
     * 该值应该设置为一个反代ip或者转发服务器ip一个值，比如array(ip1, ip2)。
     * 请注意，必须将反代的内外网ip都要设置进去，否则会获取为反代的外网ip
     */
    'trustProxyIps' => array(
        
    ),
    
    /**
     * 显示错误的页面模板路径
     * @var string
     */
    'errorPage' => 'Common/Error/Default',
    
    /**
     * Cookies的前缀
     * @var string
     */
    'cookiePre' => 'pre_',
    
    /**
     * 默认语言
     */
    'lang' => 'zh-CN',
    
);


/**
 * （默认）实例配置
 */
$config['aliasInstance'] = array(
    
    'cache' => 'DG24\Cache\Dummy',

    'router' => 'DG24\Router\QueryString',
    
    'view' => 'DG24\View\ViewRender',
    
    'session' => function($name){
        return \DG24\Session\OperatorNative::produce(\DG24\Loader::config('Session'));
    },
    
    'url' => 'DG24\Url\QueryStringUrl',
    
);

return $config;