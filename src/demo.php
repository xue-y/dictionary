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

$Ddic=new Ddic();
$Ddic->fileType='echo';// 所有数据一次直接输出到页面
/*$Ddic->limit=1;*/
/*$Ddic->isZip=true;*/

/*$Ddic->fileExt='html';*/
/*$Ddic->fileName='中文';*/
$Ddic->dbName='test';
/*$Ddic->typeDownOld=true;*/
$file=$Ddic->docFile();
print_r($file);