<?php
$config = array(
    'test1' => array(
        'test2' => 'abc',
    ),
);

$config['aliasInstance'] = array(
    'TestExampleSingle' => 'Test\Example\Example',
);

$config['aliasInstance']['TestExampleFunc'] = function($name){
    $obj = new Test\Example\Example();
    $obj->setA("TestExampleFunc");
    return $obj;
};

return $config;