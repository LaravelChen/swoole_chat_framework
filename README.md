# Swoole_Framework
![image](https://github.com/LaravelChen/swoole_framework/raw/master/Resource/images/swoole.png)

> 这是一款基于Swoole Server 开发的常驻内存型PHP框架，基于easySwoole.摆脱传统PHP运行模式在进程唤起和文件加载上带来的性能损失。EasySwoole 高度封装了Swoole Server 而依旧维持Swoole Server 原有特性，支持同时混合监听HTTP、自定义TCP、UDP协议，让开发者以最低的学习成本和精力编写出多进程，可异步，高可用的应用服务。
原文档参考 ：https://www.easyswoole.com/Manual/Cn/_book/

### 性能比较
![image](https://github.com/LaravelChen/swoole_framework/raw/master/Resource/images/a.png)

### 具体运行
进入项目目录，运行(默认端口9501)
```$xslt
php server start
或者
php server start --d 以守护进程模式开启
```
看到easySwoole的标志后请访问:http://127.0.0.1:9501
**具体的命令可以使用php server help查看使用，或者你有能力可以直接看源码哦!**