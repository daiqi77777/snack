环境<br>
Laravel >= 7.0.0<br>
PHP >= 7.2.0<br>
composer<br>

步骤<br>
1.composer install<br>
2.env(.)文件配置数据库账号密码<br>
3.执行发布资源 php artisan project:install<br>
4.执行数据迁移 php artisan db:seed --class="App\Database\SnackTableSeeder"


说明<br>
1.基础信息配置在config/snack.php<br>
     auth:sanctum 鉴权 <br>
     snack.permission 权限验证  <br>
     guards 登录认证字段 <br>
2.路由说明配置在 routes/web.php
    若无需开启鉴权与权限验证 则middleware无需传参
   