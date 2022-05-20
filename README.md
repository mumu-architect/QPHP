# QPHP

#####1.qphp是一个轻量级的phpmvc框架
#####2.支持mysql,memcache,redis
#####3.jwt生成token,和验证
#####4.项目application\admin,admin为实例代码
#####5.完成增删改查功能
#####6.新写业务参照admin模块
#####7.请求地址http://www.qphp.com/admin/user/index?id=10
###1.新增Model链式查询
```php
        $model = new UserModel();
        $arr2 = $model->table('mm_user')->asTable('u')
            ->field('u.*,ui.birthday,ui.info,a.address_info,a.is_default')
            ->leftJoin('mm_user_info as ui on ui.user_id = u.id')
            ->leftJoin('mm_address as a on a.user_id =u.id')
            ->where('u.id = 1')->findOne();
        echo '<pre>';
        print_r($arr2);
```
