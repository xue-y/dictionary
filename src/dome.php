<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/10/11
 * Time: 19:42
 * 调用测试  ---  数据表生成数据字典工具
 */
namespace Ddic;

require "../vendor/autoload.php";

// 实例化前传参数 --- 数组形式； 实例化后传参 --- 对象形式
$config['fileExt']='csv';
$Ddic=new Ddic($config);
/*$Ddic->isCreatFile=false;*/  // 直接输出到页面
/*$Ddic->limit=2;
$Ddic->isZip=true;*/
/*$Ddic->fileExt='csv';*/
$Ddic->isDown=true;
$Ddic->fileName='测试中文';

$Ddic->docFile();