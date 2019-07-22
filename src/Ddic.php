<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-5
 * Time: 下午3:23
 * 数据表生成数据字典工具
 */
namespace Ddic;

class Ddic
{
    // 配置文件
    private $config=array();
    private $pdo_conn;

    //TODO 初始化配置 连接数据库
    public function __construct($config = array())
    {
        set_time_limit(0); // 如果数据量过大可以设置一下脚本执行时间
        // 基本设置
        $this->config = array_merge(include "Config.php",$config,$this->config);
    }

    //TODO 获取配置信息
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

    // TODO 设置配置信息
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

    // TODO 判断变量是否存在
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    //TODO 设置文档title
    public function docTit()
    {
        return $this->dbName."数据库字典";
    }

    // TODO 设置文档head
    public function docHead()
    {
        $charset=$this->pdo_conn->dbCharset();
        $table_c=count($this->pdo_conn->tableAll());
        $table_c=$table_c>0?$table_c:"暂无数据表";

        $head['dbname']=$this->dbName;
        $head["charset"]=$charset;
        $head["table_info"]="数据表个数：";
        $head["table_c"]=$table_c;
        return $head;
    }

    //TODO 设置文档 body 内容
    public function docBody()
    {
        $tit=$this->pdo_conn->tableAll();
        if(empty($tit))
        {
            exit("暂无数据表");
        }

        $t_f=array(); // 表字段信息
        $t_h=array();// 表标题
        foreach($tit as $k=>$v)
        {
            $table_index=$k+1;
            $head["index"]='表: '.$table_index;  // 表序号
            $head["Name"]=$v["Name"];
            $head["Collation"]=$v["Collation"];
            $head["Engine"]=$v["Engine"];
            $head["Comment"]=$v["Comment"];
            array_push($t_h,$head);  // 表头数据---取出多余数据

            $table_field[$v["Name"]]=$this->pdo_conn->fieldCom($v["Name"]);
            array_push($t_f,$table_field);
        }
        $table_all['tit']=$t_h;
        $table_all["body"]=$t_f;
        return $table_all;
    }

    //TODO 数据字典组装数据
    public function docData()
    {
        $data['tit']=$this->docTit();
        $data['head']=$this->docHead();
        $data['body']=$this->docBody();
        return $data;
    }

    // TODO 执行数据显示、生成文件、压缩、下载
    public function docFile()
    {
        $this->initConf();
        $file_class=new File($this->config);
        $data=$this->docData();

        $data['fieldTitKey']=explode(',',$this->fieldTitKey); // 数据表字段title
        $data['fieldTitVal']=explode(',',$this->fieldTitVal); // 数据表字段信息

        $this->fileType();// 文件存储方式

        // 直接输出在当前页面 一次性输出所有数据，如果数据过多请选择创建数据文件
        if($this->config['fileType'] =='echo')
        {
            echo $file_class->docHtml($data);

        }else if($this->config['fileType'] =='down')
        {
            //下载文件
            $file_class->downFile($data);

        }else
        {
            // 生成文件保存到本地----- 如果用户没有配置参数
            $file_class->writeFile($data);
            //输出文件名与日志信息
            $file_class->outFile();
        }
    }

    //TODO 初始化数据库配置
    private function initConf()
    {
        header("Content-Type:text/html;charset={$this->config['webChar']}"); // 页面文档字符集
        date_default_timezone_set($this->config['dateTimezone']);

        // 初始化连接数据库
        $this->pdo_conn=PdoSql::getInstance($this->config);
        $this->pdo_conn->conn();
    }

    //TODO 文件存储方式设置
    private function fileType()
    {
        if(empty($this->config['fileType']) || !in_array($this->config['fileType'],explode("|",$this->config['type'])))
        {
            $this->config['fileType']=null;
        }else
        {
            // 字符统一小写
            $this->config['fileType']=strtolower($this->config['fileType']);
        }
    }
}










