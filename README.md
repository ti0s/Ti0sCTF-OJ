# Ti0sCTF-OJ 实训平台 Ver:1.0
---

* ### BackGround
由于部分学生缺少CTF练习平台，和CTFd平台搭建过程出错，特写出一个简约风格、界面友好、互动性强的CTF靶场平台。

* ### Function
1. 用户注册登录(登录后可修改密码)
2. 排行榜成绩显示(可设置单页显示多少)
3. 个人中心解题时间和擅长领域百分比
4. 答题中心判断是否答题和答题是否正确
5. 前台分类排序功能以及前台显示题目难度级别

* ### Install
由于 好多人反应 安装出现错误 故这里直接给封装成 Docker镜像 只需要两步即可启动
安装 docker 的方法 可以百度 根据自己的系统安装
`docker pull ti0s/ti0sctf:oj`
`docker run -tid -p --hostname ti0sctf 80:80 ti0s/ti0sctf:oj`

* ### Admin
前台登录默认管理员账户 `admin / ti0sctf`
访问 `/ti0s_admin` 即可进入后台

* ### Images
#### 前台
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/07/image-1024x713.png)
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/07/image-1-1024x385.png)
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/07/image-2-1024x916.png)
#### 后台
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/08/image-1024x534.png)
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/08/image-1-1024x554.png)
![TI0SCTF-OJ 演示](https://www.ti0s.com/wp-content/uploads/2020/08/image-2-1024x556.png)
* ### Other
如果在使用过程中出现问题，欢迎联系QQ：619191544 或者 Mail：Admin@Ti0s.Com，同时您也可以对本项目加以修改，维护。

* ### Exceptional
![TI0SCTF-OJ 打赏](https://www.ti0s.com/images/ds.png)

* ### 协议与许可
© Ti0s.Com

本项目使用 APACHE LICENSE VERSION 2.0 进行许可。

若您使用 Ti0s 及其相关软件、文档，即表示您已充分阅读、理解并同意接受本协议。

我们接受并允许各大高校、安全团队、技术爱好者使用 Ti0sCTF-X 作为比赛训练平台或举办内部训练赛。

但不允许在未经许可授权的情况下，使用 Ti0sCTF-X 的代码、文档、相关软件等开展商业培训、商业比赛、产品销售等任何营利性行为。禁止恶意更换、去除 Ti0sCTF-X 及其相关软件、文档版权信息。

一经发现，严肃处理。Ti0s.Com 保留追究其法律责任的权力。


