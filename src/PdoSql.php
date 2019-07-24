<?php
/**
 * 单例模式 --- 对象方式传入参数
 * PDO 连接数据库
 */
namespace Ddic;
use PDO;

class PdoSql {

    /**
     * 基本配置信息
     * @var array
     */
    private $config = array();
    // 数据连接 dsn
    private  $dsn="";
    // 定义 静态 pdo
    private static $pdo=null;
    // 定义私有类属性
    private static $_instance = null;

    /**
     * __construct
     * @todo 初始化配置
     * @param $config  array
     * */
    private function __construct($config = array())
    {
        // 合并 配置文件
        $this->config = array_merge($this->config,$config);
        $this->str_dsn();
    }

    //TODO 公有化获取实例方法
    public static function getInstance($config = array()){
        if (self::$_instance === null)
        {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    //私有化克隆方法
    private function __clone(){

    }

    //TODO 获取配置
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
    //TODO 设置属性
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

    //TODO 判断属性
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    //TODO 拼接dsn 连接字符串
    private function str_dsn()
    {
        return $this->dsn="$this->dbms:host=$this->host;port=$this->port;dbname=$this->dbName;charset=$this->char";
    }

    //TODO pdo 连接
    public  function conn()
    {
        if($this->config['longConn']==true)
        {
            $this->config['longConn']=array(PDO::ATTR_PERSISTENT => true);
        }else
        {
            $this->config['longConn']=array();
        }
        try {
            // 限制只实例化一次
            if(self::$pdo === null)
            {
              //初始化一个PDO对象
              self::$pdo = new PDO($this->dsn, $this->config['user'], $this->config['pass'],$this->config['longConn']);
              // $pdo = new PDO($this->dsn, $this->config['user'], $this->config['pass'],$this->config['longConn']);
            }
            return self::$pdo;
        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * query
     * @todo 执行sql 语句，返回数组形式数据
     * @param $sql 要执行的sql 语句
     * @param $one 是否取一条数据，默认false 取多条
     * @return array
     * */
    public function query($sql,$one=false)
    {
        $result =self::$pdo->query($sql);
        if(!isset($result))
            return false;
        if($one==true)
        {
            $arr=$result->fetch(PDO::FETCH_ASSOC);
        }else
        {
            $arr=$result->fetchAll(PDO::FETCH_ASSOC);
        }

        return $arr;
    }

    /**
     * dbCharset
     * @todo 处理数据库信息 数据库名称----字符集
     * @return string
     * */
    public function dbCharset()
    {
        $sql="show create database $this->dbName";
        $charset=$this->query($sql,true);
        //"CREATE DATABASE `back` /*!40100 DEFAULT CHARACTER SET utf8 */"
        $charset=explode("!40100",$charset["Create Database"]);
        // 去除多余字符
        $charset=trim(str_replace("*/",'',$charset[1]));
        return $charset;
    }

    /**
     * tableAll
     * @todo 获取MySQL中数据库中有所有表信息
     * @retrun array
     * */
    public function tableAll()
    {
        // show tables; 只取得表名
        // 表名+ 表结构  show tables; + show create table tableName;
        //$sql="select table_name from information_schema.TABLES where TABLE_SCHEMA='$this->dbName'";
        $sql=" show table status";
        return $this->query($sql);
    }



    /** 取得字段信息
     * @param $tablename
     * @return str
     * */
    public function fieldCom($tablename)
    {
        // 取得字段名 与 字段注释信息
       /*$sql= "select column_name,column_comment from information_schema.columns where table_schema= '$this->dbName' and table_name = '$tablename'";*/
        $sql="show full fields from $tablename";
        return $this->query($sql);
    }


    // 释放对象
    public function __destruct()
    {
        if(self::$pdo !== null)
            self::$pdo = null;
    }
}
// 调用示例
/*$singleton=PdoSql::getInstance();
$singleton->pass="admin";
$singleton->conn();
echo "<br/>";
$singleton->conn();*/