<?php
namespace qphp\Validate;

interface ValidateInterface
{
    /**
     * TODO:加载规则数据，可以不使用直接继承
     * @param $rule
     * @return Validate
     */
    public function rule($rule): ValidateInterface;

    /**
     * TODO:加载消息数据，可以不使用直接继承
     * @param array $message
     * @return Validate
     */
    public function message(array $message): ValidateInterface;

    /**
     * TODO:加载场景数据 ，可以不使用直接继承
     * @param array $scene
     * @return Validate
     */
    public function scene(array $scene): ValidateInterface;

    /**
     * 加载验证过滤数据
     * @param array $data
     * @return Validate
     */
    public function check(array $data): ValidateInterface;


    /**
     * 加载当前场景数据
     * @param array $scene
     * @return Validate
     */
    public function onScene(string $scene): ValidateInterface;

    /**
     * 设置语言
     * @param string $language
     * @return $this
     */
    public function setLanguage($language='en'):ValidateInterface;

    /**
     * 主验证方法
     * @return bool
     */
    public function Validate(): bool;

}
