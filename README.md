# QPHP

##### 1.qphp是一个轻量级的phpmvc框架
##### 2.支持mysql,oracle,memcache,redis
##### 3.jwt生成token,和验证
##### 4.项目application\admin,admin为实例代码
##### 5.完成增删改查功能
##### 6.新写业务参照admin模块
##### 7.请求地址http://www.qphp.com/admin/user/index?id=10
### 1.新增Model的mysql链式查询
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
### 2.新增Model的oracle链式查询
```php
        $model = new UserModel();
        $arr2 = $model->table('"mm_user"')->asTable('u')
            ->field('u.*,ui."birthday",ui."info",a."address_info",a."is_default"')
            ->leftJoin('"mm_user_info"  ui on ui."user_id" = u."id"')
            ->leftJoin('"mm_address"  a on a."user_id" =u."id"')
            ->where('u."id" = 1')->findOne();
        echo '<pre>';
        print_r($arr2);
```

### 3.新增验证器过滤器
###### composer require inhere/php-validate:dev-master
###### 地址：https://packagist.org/packages/inhere/php-validate
##### 方式2: 继承类 Validation
创建一个新的class，并继承 Inhere\Validate\Validation。用于一个（或一系列相关）请求的验证, 相当于 laravel 的 表单请求验证
此方式是最为完整的使用方式，可以配置规则，设置字段翻译，设置自定义的错误消息等
```php
use Inhere\Validate\Validation;

class PageRequest extends Validation
{
    # 进行验证前处理,返回false则停止验证,但没有错误信息,可以在逻辑中调用 addError 增加错误信息
    public function beforeValidate(): bool
    {
        return true;
    }
    # 进行验证后处理,该干啥干啥
    public function afterValidate(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            // 字段必须存在且不能为空
            ['tagId,title,userId,freeTime', 'required'],

            // 4<= tagId <=567
            ['tagId', 'size', 'min'=>4, 'max'=>567, 'filter' => 'int'],

            // title length >= 40. 注意只需一个参数的验证，无需加 key, 如这里的 40
            ['title', 'min', 40, 'filter' => 'trim'],

            // 大于 0
            ['freeTime', 'number'],

            // 含有前置条件
            ['tagId', 'number', 'when' => function($data) {
                return isset($data['status']) && $data['status'] > 2;
            }],

            // 在验证前会先过滤转换为 int。并且仅会在指明场景名为 'scene1' 时规则有效
            ['userId', 'number', 'on' => 'scene1', 'filter' => 'int'],
            ['username', 'string', 'on' => 'scene2', 'filter' => 'trim'],

            // 使用自定义正则表达式
            ['username', 'regexp' ,'/^[a-z]\w{2,12}$/'],

            // 自定义验证器，并指定当前规则的消息
            ['title', 'custom', 'msg' => '{attr} error msg!' ],

            // 直接使用闭包验证
            ['status', function($status) {
                if (is_int($status) && $status > 3) {
                    return true;
                }
                return false;
            }],

            // 标记字段是安全可靠的 无需验证
            ['createdAt, updatedAt', 'safe'],
        ];
    }

    // 定义不同场景需要验证的字段。
    // 功能跟规则里的 'on' 类似，两者尽量不要同时使用，以免混淆。
    public function scenarios(): array
    {
        return [
            'create' => ['user', 'pwd', 'code'],
            'update' => ['user', 'pwd'],
        ];
    }

    // 定义字段翻译
    public function translates(): array
    {
        return [
          'userId' => '用户Id',
        ];
    }

    // 自定义验证器的提示消息, 默认消息请看 {@see ErrorMessageTrait::$messages}
    public function messages(): array
    {
        return [
           // 使用验证器名字指定消息
          'required' => '{attr} 是必填项。',
          // 可以直接针对字段的某个规则进行消息定义
          'title.required' => 'O, 标题是必填项。are you known?',
        ];
    }

    // 添加一个验证器。必须返回一个布尔值标明验证失败或成功
    protected function customValidator($title): bool
    {
        // some logic ...
        // $this->getRaw('field'); 访问 data 数据

        return true; // Or false;
    }
}
```
##### 使用

```php
// 验证 POST 数据
$v = PageRequest::check($_POST);

// 验证失败
if ($v->isFail()) {
    var_dump($v->getErrors());
    var_dump($v->firstError());
}

// 验证成功 ...
$safeData = $v->getSafeData(); // 验证通过的安全数据
// $postData = $v->all(); // 原始数据

$db->save($safeData);
```
