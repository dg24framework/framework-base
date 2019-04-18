#PHPUnit命令行运行模式

全体测试：

```
phpunit --verbose --debug --coverage-html "【报告输出目录】/coverageResult.html" --testdox-html "【报告输出目录】/testResult.html" --log-tap "【报告输出目录】/testlog.txt" --configuration "【framework-base-minimal框架目录】/phpunit.xml"
```

单个测试：


```
phpunit --debug --coverage-html "【报告输出目录】/coverageResultsingle.html" --testdox-html "【报告输出目录】/testResultsingle.html" --log-tap "【报告输出目录】/testlogsingle.txt" --configuration "【framework-base-minimal框架目录】/phpunit.xml" UnitTest "【framework-base-minimal框架目录】/tests/tests/DG24/LoaderTest.php"
```