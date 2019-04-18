# DG24 - Digital Garden 24 PHP Framework (Base Minimal Component)

## 框架说明

### 前言

DG24 PHP框架全称为Digital Garden 24 PHP Framework，同时一语双关表示东莞制造。其目标是为应用开发和上线提供24x7的平稳保障。

截止2019年4月，经过5年多的实际应用，已平稳保障多个重要专题和数个大型系统的运行。

### 特性

* 基于PHPUnit + XDebug的单元测试和代码覆盖度测试

长期以来，国内框架甚少关注底层的质量保障（Quality Assurance，简称QA），故本框架效仿国外框架，在设计初期即引入PHPUnit和XDebug，通过单元测试和代码覆盖度测试，力图对框架的质量进行有效保障。

由于单元测试编写耗时太长，故暂时不要求应用层进行单元测试，仅要求框架层使用。


## 基础最小化运行组件（Base Minimal Component）说明

### 说明

该框架的基础最小化运行组件（Base Minimal Component），是整个框架的最基础核心部分。

现以开源以作非典型“测试驱动开发（又称TDD开发）”技术探讨。

非典型TDD开发的原因是，常规的TDD必须先写单元测试用例再编写代码，但此处不遵守此规则，故称为非典型TDD。


### 功能模块

为追求最小化的同时可用，仅包含的功能有：

    - 项目配置的整体管理
    - PSR-4规范的类文件自动载入
    - 类实例的单例管理
    - DI（依赖注入）服务管理
    - 类属性配置快速增强Trait
    - 类错误快速增强Trait


### 可用性

**本基础最小化运行组件已稳定，可用于测试和生产环境中。**

本基础最小化运行组件特别适合于编写小型PHP工具。

最低要求：PHP 5.4


### 文档

（正在撰写中）


## 其它


### Pull Request等贡献请求

作为一个实验性框架，其特性会存在各种不确定性，且会与本人工作范畴高度重合。

本框架的目标暂时不会做大而全框架，故如果想作贡献，建议和本人先行沟通。


### 安全问题报告

作为一名web安全爱好者，深知安全是框架型工程的百年根基。

故如发现该框架有安全问题，欢迎发邮件到 horseluke@126.com 。收到后会以尽量快的速度进行响应。


## License

DG24 framework is open-sourced software licensed under the Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0)

Last updated: 2019-4-18