<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/10/9
 * Time: 18:33
 */
return array(
    "table"     =>          array(),
    "ext"       =>          "html|csv",     //导出文件格式
    "limit"     =>          13,              // 如果分卷每卷的表个数 限制
    "minLimit" =>           1,              // 如果分卷最少表个数限制
    "maxLimit" =>           500,            // 最大表个数
    "fileDir"  =>           "./docfile/",  // 文件保存路径
    "isZip"    =>            FALSE,         // 默认 FALSE 不压缩   生成文件时、单个文件下载时是否压缩
    "fileName" =>            '',            // 文件名称 -- 不带后缀 如果文件夹存在同名文件将覆盖
    "fileExt"  =>            '',            // 文件后缀名
    "isDown"    =>           FALSE,         // 默认不下载
    'isCreatFile'=>         TRUE,           // 是否生成文件保存 默认生成文件 false直接输出在页面，格式html
    "defileExt"=>           'html',         // 文件后缀
    'dbms'      =>           'mysql',        //数据库类型
    'host'      =>           'localhost',   //数据库主机名
    'port'      =>            3306,           // 数据库端口
    'dbName'    =>           'test',         //使用的数据库
    'user'      =>           'root',         //数据库连接用户名
    'pass'      =>           'admin',          //对应的密码
    'char'      =>           'utf8',         // 数据库字符集
    'longConn' =>            FALSE,         // 是否是长连接
    'dateTimezone'=>        'PRC',          // 设置时间时区
    'webChar'=>              'UTF-8',       // 页面显示字符编码 一般浏览器默认为utf8,所有这里设置utf8
    'locationChar'=>        'GBK//TRANSLIT',                  // 本地字符编码
    'fieldTitKey' =>        '字段,类型,Null/默认,Key,注释',  // 数据表字段tit 要与 val 中的取值一一对应
    'fieldTitVal' =>        'Field,Type,Null,Key,Comment'   // 如果需要添加显示字段对应添加即可
);