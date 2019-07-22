<?php
/**
 * 错误函数处理
 * @static  ErrorCode
 */
namespace Ddic;

final class  Derror{

    /**
     * ErrorCode
     * @todo 打印错误信息并终止程序
     * @param int $code 错误码
     * @param string $errorinfo 错误信息
     * @return void  直接输出错误信息并退出
     */
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
            case 6:
                $error=$errorinfo."创建失败";
                break;
            case 7:
                $error=$errorinfo." html 模板文件不存在或文件名为中文名";
                break;
            case 8:
                $error=$errorinfo.'文件转码失败,无法压缩';
                break;
            case 9:
                $error=$errorinfo.'此函数不可用，请开启相关扩展';
                break;
            case 10:
                $error=$errorinfo.'压缩文件添加失败';
                break;
            default:
                $error="未知错误";
        }
        exit($error);
    }
}
