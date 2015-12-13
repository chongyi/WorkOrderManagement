# WorkOrderManagement

工单管理工具，小团队沟通工具。是作者平日的 laravel 框架研究中诞生的程序。该工具可用于团队之间的通知、BUG 报告、工作日报等等。

## 特色

- 简单的操作和简单的结构，一切都十分容易
- 工作组和工单的关注功能，您将会收到来自关注对象的动态信息
- 工单的追踪，您可以和一群队友有序协调工作

## 安装预配置

通过 Composer 安装

`composer create-project chongyi/work-order-management --prefer-dist`

安装完毕，编辑 `.env` 文件，编辑数据库相关配置，保存。

最后在 `public` 目录下执行 `php -S 127.0.0.1:998 ../server.php` 后，访问 `127.0.0.1:998` 即可看到登录页面。

## 前端组件

你需要安装 `npm` 和 `bower`，并下载 `ckeditor` 编辑器。

下载的 `ckeditor` 编辑器解压后放至 `public/assets/addons/ckeditor` 目录下。

在 `public` 下执行 `bower install` 安装前端组件。