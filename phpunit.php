<?php

if(!class_exists('PHPUnit_TextUI_Command', false)){
    exit('THIS IS FOR PHPUNIT RUN ONLY');
}

//模拟http
$_SERVER['HTTP_HOST'] = 'www.test.com';
$_SERVER['PHP_SELF'] = '/test/index.php';
$_SERVER['HTTP_USER_AGENT'] = "Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Mobile/10B329 MicroMessenger/5.3.1";

//框架初始化
require __DIR__. '/src/DG24/Loader.php';
$define = array(
    'D_APP_DIR' => __DIR__. '/tests/mock/app',
    'D_ENTRY_FILE' => __FILE__,
    'D_ENV' => 'PhpunitTest',
    'D_DEBUG' => 1,
);

\DG24\Loader::define($define, \DG24\Loader::AUTOLOAD_ALL);
\DG24\Loader::$app = new \DG24\SolutionBare\App();

$printPHPUnit = function($buffer = ""){
    echo PHP_EOL;
    if(!empty($buffer)){
        echo "\x1b[30;42m". $buffer. "\x1b[0m";
    }
};

$printPHPUnit();
$printPHPUnit("DG24 Framework Version: ". \DG24\Loader::VERSION);
$printPHPUnit("PHPUnit Test Prepare OK");
$printPHPUnit();
$printPHPUnit();
