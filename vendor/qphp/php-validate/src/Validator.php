<?php
namespace qphp\Validate;

use qphp\Validate\fliter\Fliter;
use qphp\Validate\language\GlobalMessage;
use qphp\Validate\Message\Message;
use \qphp\Validate\validate\Validate;

class Validator implements ValidateInterface {

    /**
     * 当前验证规则
     * @var array
     */
    protected array $rule = [];

    /**
     * 验证提示信息
     * @var array
     */
    protected array $message = [];

    /**
     * 场景
     * @var array
     */
    protected array $scene =[];

    /**
     * 当前场景
     * @var array
     */
    private string $onScene ='';

    /**
     * 需要验证的参数
     * @var array
     */
    private array $data = [];

    /**
     * 错误信息
     * @var array
     * */
    private array $error = [];

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->data = [];
        $this->error = [];
        //设置消息默认为英文
        Message::setMessage();
    }


    /**
     * TODO:加载规则数据，可以不使用直接继承
     * @param $rule
     * @return Validate
     */
    public function rule($rule): ValidateInterface
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * TODO:加载消息数据，可以不使用直接继承
     * @param array $message
     * @return Validate
     */
    public function message(array $message): ValidateInterface
    {
        $this->message = $message;
        return $this;
    }
    /**
     * TODO:加载场景数据 ，可以不使用直接继承
     * @param array $scene
     * @return Validate
     */
    public function scene(array $scene): ValidateInterface
    {
        $this->scene = $scene;
        return $this;
    }
    /**
     * 加载验证过滤数据
     * @param array $data
     * @return Validate
     */
    public function check(array $data): ValidateInterface
    {
        if(!is_array($data)) {
            $systemErrorMsg = Message::getSystemErrorMsg();
            array_push($this->error,  $systemErrorMsg['dataError']);
        }
        $this->data = $data;
        return $this;
    }


    /**
     * 加载当前场景数据
     * @param array $scene
     * @return Validate
     */
    public function onScene(string $scene): ValidateInterface
    {
        $this->onScene = $scene;
        return $this;
    }


    /**
     * 返回第一条错误信息
     * @return mixed|null
     */
    public function getError():string
    {
        return count($this->error) > 0 ? $this->error[0] : '';
    }

    /**
     * 返回所有的错误信息
     * @return array
     */
    public function getAllErrors():array
    {
        return $this->error;
    }

    /**
     * 获取当前数据
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }


    /**
     * 添加错误消息
     * @param string $error_info
     * @return bool
     */
    private function setErrorString(string $errorInfo):void
    {
        if(!empty($errorInfo)){
            array_push($this->error,  $errorInfo);
        }
    }

    /**
     * 添加多条错误信息
     * @param array $errorInfo
     */
    private function setError(array $errorInfo):void
    {
        foreach ($errorInfo as &$value){
            $this->setErrorString($value);
        }
    }

    /**
     * 设置语言
     * @param string $language
     * @return $this
     */
    public function setLanguage($language='en'):ValidateInterface
    {
        //设置消息英文
        Message::setMessage($language);
        return $this;
    }

    /**
     * 主验证方法
     * @return bool
     */
    public function Validate(): bool
    {
        $this->error = [];
        //$data = $this->data;
        //$rules = $this->rule;
        $message = $this->message;
        //获取当前场景规则
        $sceneRules = $this->getSceneRules();

        //执行验证
        foreach ($sceneRules as $key => &$item) {
            //判断规则是否合法
            if(!Message::isRuleCorrect($item)){
                continue;
            }
            //判断数据是否合法
            $errorInfo = Message::isDataCorrect($this->data,$item['fieldName']);
            if(!empty($errorInfo)){
                $this->setErrorString($errorInfo);
                continue;
            }
            $fieldValue = $this->data[$item['fieldName']];

            //验证
            $this->hybridValidate($fieldValue,$item,$message,$this->data,$this);

            //过滤
            $this->hybridFilter($fieldValue,$item,$message,$this->data,$this);
        }
        return  empty($this->error)?true:false;
    }

    /**
     * 获取当前场景对应的规则
     * @return array
     */
    private function getSceneRules():array
    {
        $onScene = $this->onScene;
        $data = $this->data;
        $rules = $this->rule;
        $sceneRules = [];

        if(array_key_exists($onScene,$this->scene)&&!empty($this->scene[$onScene])){
            $sceneArr = $this->scene[$onScene];
            foreach ($rules as &$item){
                //判断规则是否合法
                if(!Message::isRuleCorrect($item)){
                    continue;
                }
                //判断数据是否合法
                $errorInfo = Message::isDataCorrect($data,$item['fieldName']);
                if(!empty($errorInfo)){
                    $this->setErrorString($errorInfo);
                    continue;
                }
                $ruleName=$item['ruleName'];
                if(in_array($ruleName,$sceneArr)){
                    $sceneRules[] = $item;
                }
            }
        }
        return $sceneRules;
    }

    /**
     * 混合验证
     * @param $fieldValue
     * @param $item
     * @param $message
     * @param $data
     */
    private function hybridValidate($fieldValue,$item,$message,$data,$obj):void
    {
        //系统规则验证
        if(array_key_exists('validationRule',$item)&&array_key_exists('systemRule',$item['validationRule'])&&!empty($item['validationRule']['systemRule'])){
            $ruleName=$item['ruleName'];
            $validationRuleSystemRule=$item['validationRule']['systemRule'];
            $messageFieldName=$item['ruleName'].'.'.$item['fieldName'];
            $messageFieldNameKey = 'fieldName.'.$item['fieldName'];
            if(array_key_exists($messageFieldNameKey,$message[$item['ruleName']])){
                $messageFieldName=$message[$item['ruleName']][$messageFieldNameKey];
            }
            Validate::setData($data);
            $errorInfoArray=Validate::checkSystemValidation($validationRuleSystemRule,$fieldValue, $ruleName,$message,$messageFieldName);
            $this->setError($errorInfoArray);
        }
        //自定义正则验证
        if(array_key_exists('validationRule',$item)&&array_key_exists('regex',$item['validationRule'])&&!empty($item['validationRule']['regex'])){
            $ruleName=$item['ruleName'];
            $validationRuleRegex=$item['validationRule']['regex'];
            $errorInfoArray = Validate::regexValidate($validationRuleRegex,$fieldValue,$ruleName,$message);
            $this->setError($errorInfoArray);
        }
        //自定义方法验证
        if(array_key_exists('validationRule',$item)&&array_key_exists('func',$item['validationRule'])&&!empty($item['validationRule']['func'])){
            $ruleName=$item['ruleName'];
            $validationRulefunc=$item['validationRule']['func'];
            $errorInfoArray=Validate::funcValidate($validationRulefunc,$fieldValue,$ruleName,$message,$obj);
            $this->setError($errorInfoArray);
        }
    }

    /**
     * 混合过滤
     * @param $fieldValue
     * @param $item
     * @param $message
     * @param $data
     */
    private function hybridFilter($fieldValue,$item,$message,&$data,$obj):void
    {

        //系统规则过滤
        $fieldValue = $this->data[$item['fieldName']];
        if(array_key_exists('fliter',$item)&&array_key_exists('systemFilter',$item['fliter'])&&!empty($item['fliter']['systemFilter'])){
            $ruleName=$item['ruleName'];
            $fieldName=$item['fieldName'];
            $filterSystem=$item['fliter']['systemFilter'];
            $errorInfoArray = Fliter::systemFilter($filterSystem,$fieldValue,$ruleName,$fieldName,$message,$data);
            $this->setError($errorInfoArray);
        }

        //自定义正则过滤
        $fieldValue = $this->data[$item['fieldName']];
        if(array_key_exists('fliter',$item)&&array_key_exists('regex',$item['fliter'])&&!empty($item['fliter']['regex'])){
            $ruleName=$item['ruleName'];
            $fieldName=$item['fieldName'];
            $filterRegex=$item['fliter']['regex'];
            $errorInfoArray = Fliter::regexFilter($filterRegex,$fieldValue,$ruleName,$fieldName,$message,$data);
            $this->setError($errorInfoArray);
        }
        //自定义方法过滤
        $fieldValue = $this->data[$item['fieldName']];
        if(array_key_exists('fliter',$item)&&array_key_exists('func',$item['fliter'])&&!empty($item['fliter']['func'])){
            $ruleName=$item['ruleName'];
            $fieldName=$item['fieldName'];
            $filterFunc=$item['fliter']['func'];
            $errorInfoArray = Fliter::funcFilter($filterFunc,$fieldValue,$ruleName,$fieldName,$message,$data,$obj);
            $this->setError($errorInfoArray);
        }
    }
}
