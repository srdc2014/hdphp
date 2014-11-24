<?php
// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <2300071698@qq.com>
// | Copyright (c) 2012-2013, http://houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------
import('HDPHP.Lib.Driver.View.Tag');

/**
 * HD模板引擎编译处理类
 * @package     View
 * @subpackage  HDPHP模板
 * @author      后盾向军 <2300071698@qq.com>
 */
class ViewCompile
{
    /**
     * HdView视图对象
     * @var Object
     */
    private $view;
    /**
     * 模板编译内容
     * @var
     */
    private $content;
    /**
     * 别名函数
     * @var array
     */
    private $aliasFunction = array(
        'default' => '_default'
    );

    /**
     * @param Object $view HdView对象
     */
    function __construct()
    {

    }

    //运行编译
    public function run(&$view = null)
    {
        /**
         * HdView对象
         */
        $this->view = $view;
        /**
         * 模板内容
         */
        $this->content = file_get_contents($this->view->tplFile);
        /**
         * 加载标签类
         * 标签由系统标签与用户扩展标签构成
         */
        $this->parseTag();
        /**
         * 解析变量
         */
        $this->parseVar();
        /**
         * 将所有常量替换   如把__APP__进行替换
         */
        $this->parseUrlConst();
        /**
         * 解析POST令牌Token
         */
        $this->parseTokey();
        /**
         * 将Literal内容替换
         */
        $this->replaceLiteral();
        $this->content = '<?php if(!defined("HDPHP_PATH"))exit;C("SHOW_NOTICE",FALSE);?>' . $this->content;
        /**
         * 创建编译目录与安全文件
         */
        Dir::create(dirname($this->view->compileFile));
        Dir::safeFile(dirname($this->view->compileFile));
        /**
         * 储存编译文件
         */
        file_put_contents($this->view->compileFile, $this->content);
    }

    /**
     * 加载标签库与解析标签
     */
    private function parseTag()
    {
        /**
         * 所有标签库类
         */
        $tagClass = array();
        /**
         * 框架标签库
         */
        if (import('HDPHP.Lib.Driver.View.ViewTag')) {
            $tagClass[] = 'ViewTag';
        }
        /**
         * 用户定义标签库
         */
        $tags = C('TPL_TAGS');
        /**
         * 导入用户定义标签库
         */
        if (!empty($tags) && is_array($tags)) {
            /**
             * 压入标签类
             */
            foreach ($tags as $file) {
                /**
                 * 导入标签类
                 */
                if (import($file) || import($file, MODULE_TAG_PATH) || import($file, APP_TAG_PATH)) {
                    //类名
                    $class = basename($file);
                    /**
                     * 合法标签类必须包含Tag属性
                     */
                    if (class_exists($class, false) && property_exists($class, 'tag') && get_parent_class($class) == 'Tag') {
                        $tagClass[] = $class;
                    }
                }
            }
        }
        /**
         * 解析标签类
         */
        foreach ($tagClass as $class) {
            /**
             * 标签类对象
             */
            $obj = new $class();
            /**
             * 标签库中的标签方法
             */
            foreach ($obj->tag as $tag => $option) {
                /**
                 * 合法标签满足以下条件
                 * b) 定义了block与level值
                 */
                if (!isset($option['block']) || !isset($option['level'])) {
                    continue;
                }
                /**
                 * 解析标签
                 */
                for ($i = 0; $i <= $option['level']; $i++) {
                    if (!$obj->parseTag($tag, $this->content)) {
                        break;
                    }
                }
            }
            /**
             * 释放对象
             */
            unset($obj);
        }
    }

    /**
     * 不解析内容即literal标签包裹内容
     */
    private function replaceLiteral()
    {
        $literal = ViewTag::$literal;
        foreach ($literal as $id => $content) {
            $this->content = str_replace('###hd:Literal' . $id . '###', $content, $this->content);
        }
        ViewTag::$literal = array();
    }

    /**
     * 解析变量
     * @return mixed
     */
    private function parseVar()
    {
        $preg = '#\{(\$[\w\.]+)?(?:\|(.*))?\}#isU';
        $status = preg_match_all($preg, $this->content, $info, PREG_SET_ORDER);
        if ($status) {
            foreach ($info as $d) {
                /**
                 * 变量
                 */
                $var = '';
                if (!empty($d[1])) {
                    $data = explode('.', $d[1]);
                    foreach ($data as $n => $m) {
                        if ($n == 0) {
                            $var .= $m;
                        } else {
                            $var .= '[\'' . $m . '\']';
                        }
                    }
                }
                /**
                 * 函数
                 */
                if (!empty($d[2])) {
                    $functions = explode('|', $d[2]);
                    foreach ($functions as $func) {
                        /**
                         * 函数解析
                         * 如:substr:0,2
                         */
                        $tmp = explode(':', $func, 2);
                        /**
                         * 函数名
                         * 别名函数中存在时，使用别名函数
                         */
                        if ($this->aliasFunction[$tmp[0]]) {
                            $name = $this->aliasFunction[$tmp[0]];
                        } else {
                            $name = $tmp[0];
                        }
                        //参数
                        $arg = empty($tmp[1]) ? '' : $tmp[1];
                        /**
                         * 变量加入到参数中
                         * 参数中有@@时将变量替换@@
                         */
                        if (strstr($arg, '@@')) {
                            $var = str_replace('@@', $var, $arg);
                        } else {
                            $var = $var . ',' . $arg;
                        }
                        /**
                         * 删除参数连接后的尾部逗号
                         */
                        $var = trim($var, ',');
                        $var = $name . '(' . $var . ')';
                    }
                }
                $replace = '<?php echo ' . $var . ';?>';
                $this->content = str_replace($d[0], $replace, $this->content);
            }
        }
    }


    /**
     * 解析Token
     */
    private function parseTokey()
    {
        if (!C("TOKEN_ON")) return;
        Token::create(); //生成token
        $preg = '/<\/form>/iUs';
        $content = '<input type="hidden" name="<?php echo C("TOKEN_NAME");?>" value="<?php echo $_SESSION[C("TOKEN_NAME")]?>"/></form>';
        $this->content = preg_replace($preg, $content, $this->content);
    }

    /**
     * 替换URL地址常量
     * 如__CONTROLLER__
     */
    private function parseUrlConst()
    {
        $const = get_defined_constants(true);
        foreach ($const['user'] as $k => $v) {
            if (strstr($k, '__')) {
                $this->content = str_replace($k, $v, $this->content);
            }
        }
    }
}