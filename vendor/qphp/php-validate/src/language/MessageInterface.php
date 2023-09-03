<?php


namespace qphp\Validate\language;


interface MessageInterface
{
    /**
     *初始化全局消息
     */
    public static function init():void;

    /**
     * 设置全局消息
     */
    public static function setLanguage(string $en='en'): void;
}
