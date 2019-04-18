<?php

namespace DG24\StdTrait;

use Test\Service\TestRunService;
use Test\Service\TestService;
use DG24\Error\CommonError;

class PropertyLastErrorStdTraitTest extends \PHPUnit_Framework_TestCase{

    protected $testInstance;

    protected function setUp(){
        parent::setUp();
        $this->testInstance = new TestRunService();
    }

    public function testBeforeErrorGetLastErrorCode(){
        $this->assertEquals(0, $this->testInstance->getLastErrorCode());
        $this->assertEquals(null, $this->testInstance->getLastErrorMsg());
        $this->assertEquals(null, $this->testInstance->getLastErrorExtra());

        $summary = $this->testInstance->getLastErrorExtra();
        $this->assertEquals(0, $summary["error_code"]);
    }

    public function testBeforeErrorGetLastErrorSummary(){
        $summary = $this->testInstance->getLastErrorSummary();
        $this->assertEquals(0, $summary["error_code"]);
    }

    public function testAfterErrorGetLastErrorCode(){
        $this->testInstance->setLastError(TestRunService::ERROR_TEST, TestRunService::ERRORCODE_TEST);
        $this->assertEquals(TestRunService::ERRORCODE_TEST, $this->testInstance->getLastErrorCode());
        $this->assertEquals(TestRunService::ERROR_TEST, $this->testInstance->getLastErrorMsg());

        $summary = $this->testInstance->getLastErrorSummary();
        $this->assertEquals(TestRunService::ERRORCODE_TEST, $summary["error_code"]);
        $this->assertEquals(TestRunService::ERROR_TEST, $summary["error_msg"]);
    }


    public function testSetLastErrorHideExtra(){

        $extra = array(
            "id" => "xxx_". mt_rand(),
        );

        $this->testInstance->setLastError("ERROR", -1, $extra);

        $this->assertTrue($this->testInstance->getLastErrorHideExtra());
        $this->assertEquals(null, $this->testInstance->getLastErrorExtra());

        $this->testInstance->setLastErrorHideExtra(false);
        $this->assertFalse($this->testInstance->getLastErrorHideExtra());
        $fetchExtra = $this->testInstance->getLastErrorExtra();
        $this->assertEquals($extra["id"], $fetchExtra["id"]);


    }


    public function testGetLastErrorSummaryWithHideExtra(){
        $extra = array(
            "id" => "xxx_". mt_rand(),
        );

        $this->testInstance->setLastError("ERROR", -1, $extra);

        $summary = $this->testInstance->getLastErrorSummary(true);
        $this->assertEquals(null, $summary["error_extra"]);
    }


    public function testGetLastErrorSummaryWithOutHideExtra(){
        $extra = array(
            "id" => "xxx_". mt_rand(),
        );

        $this->testInstance->setLastError("ERROR", -1, $extra);

        $summary = $this->testInstance->getLastErrorSummary(false);
        $this->assertEquals($extra["id"], $summary["error_extra"]["id"]);
    }


    public function testClearLastError(){

        $this->assertEquals(0, $this->testInstance->getLastErrorCode());

        $this->testInstance->setLastError("ERROR", -1);

        $this->assertEquals(-1, $this->testInstance->getLastErrorCode());

        $this->testInstance->clearLastError();

        $this->assertEquals(0, $this->testInstance->getLastErrorCode());


    }


    public function testSetLastErrorOverwrite(){

        $err_code = mt_rand(1, 1000);

        $this->testInstance->setLastError("ERROR", $err_code);

        $srv2 = new TestService();
        $this->assertEquals(0, $srv2->getLastErrorCode());

        $srv2->setLastErrorOverwrite($this->testInstance);
        $this->assertEquals($err_code, $this->testInstance->getLastErrorCode());
        $this->assertEquals($err_code, $srv2->getLastErrorCode());
    }

    public function testSetLastErrorFromCommonError(){
        $code = -333;
        $commonError = new CommonError("error", "",  $code);

        $this->testInstance->setLastErrorFromCommonError($commonError);
        $this->assertEquals($code, $this->testInstance->getLastErrorCode());

    }

}
