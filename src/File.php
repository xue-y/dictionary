<?php
/**
 * 操作文件类
 */
namespace Ddic;
use ZipArchive;

class File {

    private $config=array(
        'tempFile         '=>     "./file/temp.php", // html 模板文件
        'logFile'           =>      './log/error.txt',  // 错误警告文件日志 log 文件夹要有读写创建的权限
        'logTimeFormat'    =>      'Y:m:d H:i:s',
        'logTipsInfo'      =>      '执行时有警告错误请查看日志信息',
        'excleBr'           =>      PHP_EOL,     //excle 文件换回符
        "defileExt"         =>     'csv',         // 文件后缀
    );
    private  $logError=FALSE;       // log 错误提示信息
    private  $tmpFileName;          // 写入文件后返回的文件名
    private  $isSubsection=false;  // 是否分卷

    // 初始化配置文件-- 这里的配置信息要数组形式调用
    public function __construct($config=array())
    {
        ini_set("auto_detect_line_endings",TRUE);
        $this->config = array_merge($config,$this->config);
        // 判断文件存放目录创建是否成功
        if(!$this->mkdirFile($this->config['fileDir']))
        {
            Derror::ErrorCode(3,$this->config['fileDir']);
        }
        // 判断日志存放目录创建是否成功
        if(!$this->mkdirFile(dirname($this->config['logFile'])))
        {
            Derror::ErrorCode(3,dirname($this->config['logFile']));
        }

        $this->isExt(); // 判断文件格式是否合法
        $this->isMinMax();// 判断数据条数
        $this->fileName();// 调用文件名称
        // 文件名定义-
        $this->tmpFileName=$this->dataFileName();
    }

    /** 写入文件
     * @parem $data 要写入文件的数据
     * @return viod
     * */
    public function  writeFile($data)
    {
        if($this->config['fileExt']=='html')
        {
            // 写入html
           $this->writeHtml($data);
        }else
        {
            // 写入csv
            $this->writeCsv($data);
        }
        // 判断是否执行压缩
        $this->docZip($this->tmpFileName);

        // 输出文件名与日志信息  调用下载函数 downFile 是 输出不出来页面直接下载了
        // $this->outFile();
    }

    /* 下载文件*/
    public function downFile($data)
    {
        $this->writeFile($data);
        $this->docDown($this->tmpFileName);
    }

    /** 输出文件执行后的结果
     * 输出文件名、如果存在日志警告信息输出警告
     * */
    public function outFile()
    {
        echo '生成文件路径：<br/>';
        // 文件名（gbk-->locationChar）与网页 (utf8--->webChar) 编码一致
        $this->tmpFileName=$this->fileNameCode($this->tmpFileName);

        // 输出
        var_dump($this->tmpFileName);

        // 如果存在日志警告信息 输出
        if($this->logError==true)
        {
            echo $this->config["logTipsInfo"].$this->config['logFile'];
        }
    }

    /** 数据写入 html 文件
     * @parem $data 数据key  tit,head, body(tit,body)
     * @return 属性赋值 viod
     * */
    private function  writeHtml($data)
    {
        $c=$data['head']["table_c"];// 数据总条数

        if($this->config['limit']<$c)
        {
            //--------------------------------------------------------------------------多个文件处理
            $new_data=$data;
            unset($new_data["body"]["tit"]);

            // 类似分页
            $new_c=$c;
            $offset=0;
            $page=0;
            $file_name_arr=array();
            while($new_c>0)
            {
                $new_data['body']['tit']=array_slice($data['body']['tit'],$offset,$this->config['limit'],true);

                $file_name_arr[$page]=$this->tmpFileName."_$page.".$this->config["fileExt"];
                file_put_contents($file_name_arr[$page],$this->docHtml($new_data));
                if(!file_exists($file_name_arr[$page])) // 文件创建失败
                {
                    Derror::ErrorCode(2);
                }
                $page++;
                $offset=$page*$this->config['limit'];
                $new_c-=$this->config['limit'];
            }
            $this->tmpFileName=$file_name_arr;

        }else{
            // -----------------------------------------------------------------------------------单个文件处理
            $this->tmpFileName.=".".$this->config["fileExt"];

             file_put_contents($this->tmpFileName,$this->docHtml($data));
            if(!file_exists($this->tmpFileName)) // 文件创建失败
            {
                Derror::ErrorCode(5);
            }
        }//------------------------------------------------------------------文件创建完成
    }

    /** 写入Csv文件
     * @parem $data 数据key  tit,head, body(tit,body)
     * @return  属性赋值 viod
     * */
    private function writeCsv($data)
    {
        $c=$data['head']["table_c"];// 数据总条数
        if($this->config['limit']<$c)
        {
            $new_data=$data;
            unset($new_data["body"]["tit"]);

            // 类似分页
            $new_c=$c;
            $offset=0;
            $page=0;
            $file_name_arr=array();
            while($new_c>0)
            {
                $new_data['body']['tit']=array_slice($data['body']['tit'],$offset,$this->config['limit'],true);

                $file_name_arr[$page]=$this->tmpFileName."_$page.".$this->config["fileExt"];
                $this->arrWriteCsv($new_data,$file_name_arr[$page]);

                $page++;
                $offset=$page*$this->config['limit'];
                $new_c-=$this->config['limit'];
            }
            $this->tmpFileName=$file_name_arr;
        }
        else
        {
            // 单个文件写入
            $this->tmpFileName.=".".$this->config["fileExt"];
            // arr write csv
            $this->arrWriteCsv($data,$this->tmpFileName);
        }
    }

    /** 返回 html 格式的数据
     * @parem $data arr
     * @return  html
     * */
    public function docHtml($data)
    {
        ob_start(); // --------------------控制输出量
        $this->isTemp(); // 判断模板文件是否存在
        require $this->config['tempFile'];  // 载入模板文件
        $c=ob_get_contents();
        ob_clean();
        return $c;
    }

    /** 执行一个或多个文件压缩
     * @parem $filename 需要压缩的文件名 str or arr
     * @return $zipname
     * */
    private function docZip($filename)
    {
        // 判断是否压缩  --- 属性赋值 不需要返回值
        if($this->config['isZip']==TRUE)
        {
            $this->tmpFileName=$this->zipFile($filename);
        }else
        {
            $this->tmpFileName=$filename;
        }
    }

    /**执行下载文件
     * @parem $filename 需要下载的文件名 str or arr
     * @return void
     */
    private function docDown($filename)
    {
        // 判断文件是不是数组如果是数组，就是没有压缩的文件， 执行压缩---- 多个文件
        // 如果不是数组 ---- 单个文件执行下载
        if(is_array($filename))
        {
            $filename=$this->zipFile($filename);
        }

        if($this->config['typeDownHold']==true)  // 判断下载完成是否保留本地文件
        {
            $this->exctDownLocalFile($filename);
        }else
        {   // 下载完成删除本地文件
            $this->exctDownFile($filename);
        }
    }


    /*压缩一个或多个文件,如果存在同名压缩文件夹自动覆盖
    * @parem $filename str 单个文件 arr 多个文件
     * @return str  zip_file_name
     * */
    private function zipFile($filename)
    {
        //压缩文件名如果是中文需要转码
        $zipname=$this->config['fileDir'].$this->charCode(date('Y-m-d-His')."_dict.zip");

        $zip=new ZipArchive();
        if(!$zip->open($zipname,ZipArchive::OVERWRITE)===TRUE)
        {
            Derror::ErrorCode(6,$zipname);
        }

        /* 如果$filename 是 arr 时 自身循环一次, 添加压缩是又要for 循环一次，共循环 2 次*/
        // $new_file_name=$this->zipFileRename($filename);
        if(is_array($filename))
        {
            foreach($filename as $k=>$fv)
            {
                //不包含路径文件夹 如果存在同名文件将会覆盖
               $zip->addFile($fv,$this->zipFileRename($fv));
            }
        }else
        {
           $zip->addFile($filename,$this->zipFileRename($filename));
        }

        $zip->close();

        //如果压缩 文件创建失败
        if(!file_exists($zipname))
        {
            Derror::ErrorCode(4);
        }
        // 删除源文件
        $this->unFile($filename);

        return $zipname;
    }



    /** 删除压缩后的原文件
     * @parem $filename str 单个文件 arr 多个文件
     * 失败写入日志，成功返回true
     * */
    private function unFile($filename)
    {
        if(is_array($filename))
        {
            foreach ($filename as $v)
            {
                $this->unFile($v);
            }
        }else
        {
            if(!@unlink($filename))
            {
                $this->log($filename.'删除失败');
                $this->logError=true; // 记录一下log 日志中存在错误信息
            }
        }
    }

    /** 警告信息写入日志
     * @parem $message 需要写入的log 日志信息
     * */
    private function log($message)
    {
        $file_info=pathinfo($this->config["logFile"]);
        $this->mkdirFile($file_info["dirname"]);

        $type="[Notice] ";
        $data=date($this->config['logTimeFormat']);
        $br=PHP_EOL;
        $info=$type.$data.' [Message]：'.$message.$br;

        error_log($info,3,$this->config["logFile"]);
    }

    /* 文件名处理
    */
    private function dataFileName()
    {
        return $this->config['fileDir'].$this->charCode($this->config["fileName"]);
    }

    // 判断文件格式
    private function isExt()
    {
        if(empty($this->config['fileExt']))
        {
            $this->config['fileExt']=$this->config['defileExt'];

        }else if(!in_array($this->config['fileExt'],explode("|",$this->config['ext'])))
        {
            Derror::ErrorCode(1);
        }
        // 字符统一小写
        $this->config['fileExt']=strtolower($this->config['fileExt']);
    }

    // 文件分卷，每卷条数限制
    private function isMinMax()
    {
        $this->config['limit']=max($this->config['minLimit'],$this->config['limit']);
        $this->config['limit']=min($this->config['maxLimit'],$this->config['limit']);
    }

    // 判断 html 模板文件是否 存在
    private function isTemp()
    {
        if(!file_exists($this->config['tempFile']))
        {
            Derror::ErrorCode(7,$this->config['tempFile']);
        }
    }

    // 文件名称
    private function fileName()
    {
        if(empty($this->config['fileName']))
        {
            $this->config['fileName']=time();
        }
    }

    // 文件名字符转码 这里简单处理，只对于中文字符 gbk utf8 ,其他编码未测试
    private function charCode($str)
    {
        if(strlen($str)!=mb_strlen($str,$this->config['webChar']))
        {
            $str=iconv($this->config['webChar'],$this->config['locationChar'],$str);
        }
        return $str;
    }

    /**文件名转码，与页面一致 gbk 转 utf8
     * @parem $filename 文件名
     * @parem $prefix  添加统计文件前缀
     * @return filename str or arr
     * */
    private function fileNameCode($filename,$prefix='')
    {
        if(!is_array($filename))
        {
            if(strlen($filename)!=mb_strlen($filename,$this->config['webChar']))
            {
                $filename=iconv($this->config['locationChar'],$this->config['webChar'],$filename);
            }
            return $prefix.$filename;
        }
        foreach ($filename as $v)
        {
            $new_fn[]=$this->fileNameCode($v,$prefix);
        }
         return $new_fn;
    }

    /** 从新更名压缩文件夹中的文件名,去除嵌套的文件夹
     * @parem $filename str or arr
     * @return filename str or arr
     * */
    private function zipFileRename($filename)
    {
        if(!is_array($filename))
        {
            $f_arr=explode("/",$filename);
            return end($f_arr);
        }

        foreach ($filename as $v)
        {
            $f_n[]=$this->zipFileRename($v);
        }
        return $f_n;
    }

    /* 创建 $dir 返回 bool */
    private function mkdirFile($dir)
    {
        if(!is_dir($dir))
        {
            return @mkdir($dir,0777,true);
        }
        return true;
    }

    /**数组数据写入 Csv 文件
     * @parem $data arr
     * @parem $filename  文件名
     * @return viod
     * */
    private function arrWriteCsv($data,$filename)
    {
        $file_resource=fopen($filename,'w+');
        if(!$file_resource)
        {
            Derror::ErrorCode(5);
        }
        $br=$this->config['excleBr'];
        // utf8 编码数据写入 csv 文件，设置 BOM 头，否则乱码
        // 数据编码 要与写入 csv 文件的编码一致，也可以将数据转 gbk 写入 csv, Excel打开也不乱码，只是这样比较麻烦
        fwrite($file_resource,chr(0xEF).chr(0xBB).chr(0xBF));
        $tit=$data['tit'].$br;
        fwrite($file_resource,$tit);
        fputcsv($file_resource,$data['head']);
        fwrite($file_resource,$br);

        foreach ($data['body']['tit'] as $k=>$v)
        {
            fputcsv($file_resource,$v);
            fputcsv($file_resource,$data['fieldTitKey']);

            foreach ($data['body']['body'][$k][$v['Name']] as $vv)
            {
                $vv_key=array_diff(array_keys($vv),$data['fieldTitVal']);
                if(!empty($vv_key))
                {
                    foreach ($vv_key as $vvv)
                    {
                        unset($vv[$vvv]);
                    }
                }
                fputcsv($file_resource,$vv);
            }
            fwrite($file_resource,$br);
        }
        fclose($file_resource);
        if(!file_exists($filename)) // 文件创建失败
        {
            Derror::ErrorCode(5);
        }
        ob_clean();// 清空缓冲区
    }

    /** 本地已创建的文件下载
     * @parem $filename 下载的文件名
     * @return 直接输出文件
     */
    private function exctDownLocalFile($filename)
    {
        $file_ext=pathinfo($filename,PATHINFO_EXTENSION);
        header("Content-type:application/".$file_ext);
        // 此处可以省略
        /*$f_size=filesize($filename);
        header("Accept-Ranges:bytes");
        header("Accept-Length:".$f_size);*/
        $f_arr=explode("/",$filename);
        $new_file_name=end($f_arr);
        header("Content-Disposition:attachment;filename=".$new_file_name);
        header("Content-Transfer-Encoding:binary");
        readfile($filename,true);
    }
    // 判断用户是否下载完成或取消下载 删除本地文件
    private function exctDownFile($filename)
    {
        $fp=fopen($filename,"r");
        $file_ext=pathinfo($filename,PATHINFO_EXTENSION);
        header("Content-type:application/".$file_ext);

        $f_size=filesize($filename);
        header("Accept-Ranges:bytes");
        header("Accept-Length:".$f_size);

        $f_arr=explode("/",$filename);
        $new_file_name=end($f_arr);
        header("Content-Disposition:attachment;filename=".$new_file_name);
        header("Content-Transfer-Encoding:binary");

        $buffer=1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
        $file_count=0; //读取的总字节数
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$f_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
        //下载完成后删除压缩包，临时文件夹
        if($file_count >= $f_size)
        {
            $this->unFile($filename);
        }
    }

}

