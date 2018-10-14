<?php
/** 错误函数处理
 */
namespace Ddic;

/** 错误信息，之间终止程序
 * @parem $code 错误码
 * @parem $errorinfo 错误信息
 * @return exit 直接输出错误信息并退出
 * */
final class  Derror{
     static function ErrorCode($code,$errorinfo='')
    {
        switch($code)
        {
            case 1:
                $error="文件后缀名不合法,请查看配置文件中允许的后缀名";
                break;
            case 2:
                $error="html 文件创建失败";
                break;
            case 3:
                $error=$errorinfo."文件目录创建失败";
                break;
            case 4:
                $error="创建压缩文件失败,源文件创建成功";
                break;
            case 5:
                $error="创建 csv 文件失败";
                break;
            default:
                $error="未知错误";
        }
        exit($error);
    }
}
