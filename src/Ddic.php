<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-5
 * Time: 下午3:23
 * 数据表生成数据字典工具
 */
namespace Ddic;
use Ddic\PdoSql;
use Ddic\File;

class Ddic extends  PdoSql
{
    // 配置文件
    private $config ;

    //初始化配置 连接数据库
    public function __construct($config = array())
    {
        //set_time_limit(30) // 如果数据量过大可以设置一下脚本执行时间
        // 基本设置
        $this->config = array_merge(include "Config.php",$config);
        header("Content-Type:text/html;charset={$this->config['webChar']}"); // 页面文档字符集
        date_default_timezone_set($this->config['dateTimezone']);

        // 初始化连接数据库
        $pdo_conn=self::getInstance($this->config);

        $pdo_conn->conn();
    }

    // 设置获取 配置信息
    public function __get($name)
    {
        if(isset($this->config[$name]))
        {
            return $this->config[$name];
        }else
        {
            exit("No $name variable exists,Unable to obtain");
        }
    }

    public function __set($name,$value)
    {
        if(isset($this->config[$name]))
        {
            $this->config[$name] = $value;
        }else
        {
            exit("No $name variable exists,Unable to set up");
        }
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /* 文档tit */
    public function docTit()
    {
        return $this->dbName."数据库字典";
    }

    /* 文档 head */
    public function docHead()
    {
        $charset=$this->dbCharset();
        $table_c=count($this->tableAll());
        $table_c=$table_c>0?$table_c:"暂无数据表";

        $head['dbname']=$this->dbName;
        $head["charset"]=$charset;
        $head["table_info"]="数据表个数：";
        $head["table_c"]=$table_c;
        return $head;
    }

    /* 文档body */
    public function docBody()
    {
        $tit=$this->tableAll();
        if(empty($tit))
            return false;

        $t_f=array(); // 表字段信息
        $t_h=array();// 表标题
        foreach($tit as $k=>$v)
        {
            $head["index"]=$k+1;
            $head["Name"]=$v["Name"];
            $head["Collation"]=$v["Collation"];
            $head["Engine"]=$v["Engine"];
            $head["Comment"]=$v["Comment"];
            array_push($t_h,$head);  // 表头数据---取出多余数据

            $table_field[$v["Name"]]=$this->fieldCom($v["Name"]);
            array_push($t_f,$table_field);
        }
        $table_all['tit']=$t_h;
        $table_all["body"]=$t_f;
        return $table_all;
    }

    // 所有的数据
    public function docData()
    {
        $data['tit']=$this->docTit();
        $data['head']=$this->docHead();
        $data['body']=$this->docBody();
        return $data;
    }

    /* 显示、生成文件、压缩、下载*/
    public function docFile()
    {
        $file_class=new File($this->config);
        $data=$this->docData();

        $data['fieldTitKey']=explode(',',$this->fieldTitKey); // 数据表字段title
        $data['fieldTitVal']=explode(',',$this->fieldTitVal); // 数据表字段信息

        // 直接输出在当前页面 一次性输出所有数据，如果数据过多请选择创建数据文件
        if($this->config['isCreatFile'] != true)
        {
            echo $file_class->docHtml($data);
        }else
        {
           // 写入文件 或 下载文件
           $file_class->writeFile($data);
        }
    }

}










