<?php
namespace qphp\Validate\language;


final class GlobalMessage implements MessageInterface{

    private static array $messages = [];

    private static array $languageConf = [
        'en'=>'qphp\Validate\language\LocaleEN',
        'cn'=>'qphp\Validate\language\LocaleZhCN',
    ];


    /**
     * 获取消息
     * @param string $systemKey
     * @param string $key
     * @return mixed
     */
    public static function get(string $systemKey,string $key): mixed
    {
        return self::$messages[$systemKey][$key] ?? '';
    }

    /**
     * 设置消息
     * @param string $systemKey
     * @param string $key
     * @param array $msg
     */
    public static function set(string $systemKey, array $msg): void
    {
        foreach (self::$messages[$systemKey] as $key=>$value ){
            if ($key && $msg) {
                self::$messages[$systemKey][$key] = $msg;
            }
        }
    }

    /**
     * 判断消息是否存在
     * @param string $systemKey
     * @param string $key
     * @return bool
     */
    public static function has(string $systemKey,string $key): bool
    {
        return isset(self::$messages[$systemKey][$key]);
    }

    /**
     * 设置所有消息
     * @param string $systemKey
     * @param array $messages
     */
    public static function setMessages(array $messages): void
    {
        foreach ($messages as $systemKey => $value) {
            self::set($systemKey, $value);
        }
    }

    /**
     * 获取所有消息
     * @return array
     */
    public static function getMessages(): array
    {
        return self::$messages;
    }


    /**
     * 判断语言类是否存在
     * @param string $en
     * @return bool
     */
    private static function hasLanguageConf(string $en):bool
    {
        if(!empty($en)&& isset(self::$languageConf[$en])){
            return true;
        }
        return false;
    }

    /**
     * 获取指定语言类
     * @param string $en
     * @return mixed
     */
    private static function getLanguage(string $en='en')
    {
        if(self::hasLanguageConf($en)){
            return self::$languageConf[$en];
        }
        return self::$languageConf['en'];
    }

    /**
     *初始化全局消息
     */
    public static function init():void
    {
        $language = self::getLanguage();
        self::$messages=$language::getMessages();
    }

    /**
     * 设置全局消息
     */
    public static function setLanguage(string $en='en'): void
    {
        self::$messages=self::getLanguage($en)::getMessages();
    }
}
