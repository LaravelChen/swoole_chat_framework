# Swoole_Chat_Framework
> 本项目采用swoole作为首选，采用easySwoole作为首选框架，它是一款基于Swoole Server 开发的常驻内存型的分布式PHP框架，专为API而生，摆脱传统PHP运行模式在进程唤起和文件加载上带来的性能损失。
EasySwoole 高度封装了 Swoole Server 而依旧维持 Swoole Server 原有特性，支持同时混合监听HTTP、自定义TCP、UDP协议，让开发者以最低的学习成本和精力编写出多进程，可异步，高可用的应用服务。
具体可以看文档https://www.easyswoole.com/Manual/2.x/Cn/_book/

### 1.简介
本人为了更加便利的开发，自行实现了中间件，封装了请求数据体，利用jwt实现api的token验证，集成了Laravel的ORM，
再次封装了一套适合api编写流程的数据请求流程，具体可以看App/Base目录下的Model类，具体开发步骤详见代码即可。

### 2.主要实现
- 登录注册，验证码发送（如果需要测试，可以结合前端react将验证码打印出来即可）
- 公共聊天室（一旦用户登录，用户列表即会增加，该用户可以进行加好友操作）
- 消息推送（可以利用swoole的异步进程实现）
- 私聊室 （加完好友即可进行私聊）
- 其余功能可以添加......

### 3.安装
**这里只是后台逻辑，前端的对应项目请移步到:** https://github.com/LaravelChen/React-Small-Chat
```
php server start
```
因为swoole常驻内存，所以一旦修改代码，需要重启。


### 4.项目效果
#### 1.1 畅聊室
![image](https://github.com/LaravelChen/React-Small-Chat/raw/master/screen/image1.gif)
![image](https://github.com/LaravelChen/React-Small-Chat/raw/master/screen/image2.gif)
#### 1.2 私聊室
![image](https://github.com/LaravelChen/React-Small-Chat/raw/master/screen/image3.gif)
![image](https://github.com/LaravelChen/React-Small-Chat/raw/master/screen/image4.gif)

**此外，还有其他的加好友，消息推送等效果不演示了，可以自行下载安装使用，效果很好!**