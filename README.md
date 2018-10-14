#### 项目说明
通过配置 MySql 数据库信息，生成数据表字典  
可以输出在当前页面，可以生成文件保存在指定位置，也可以下载    
格式支持网页HTML格式、CSV格式（Excel 读取）、ZIP压缩格式      
数据库类型 MySql ,数据库连接方式使用 PDO , PHP 版本建议 5.5 以上  
生成的文件名可以自定义，支持中文  
如果是其他编码可以通过配置文件中`locationChar` `webChar`配置  
如果是生成文件保存到指定目录，输出的是数据文件目录地址，可以通过 File 类中的 outFile() 函数修改输出你自己想要的信息  
支持分卷，限制每个文件写入多少张表，防止数据表过多文件打开时响应时间过长

**注意：**  如果生成的 csv 文件中的数据是中文的，2007 版本的 office/Excel 打开乱码，高版本没有问题


#### 文件说明
dictionary.zip  如果没有你的项目中没有引入自动加载，可以直接使用 dictionary.zip 解压放在你指定目录        
src/     
|---docfile/		创建文件存放目录,可以通过配置自定义          
|---file/			HTML 模板文件与样式文件,可以通过配置自定义      
|---log/			日志文件夹,可以通过配置自定义       
|---test/			示例图片目录       
|---.gitignore		GitHub 忽略文件      
|---Config.php		配置文件     
|---Ddic.php		生成数据字典核心类     
|---Derror.php		错误处理类文件     
|---File.php		生成 HTML/CSV 数据文件    
|---PdoSql.php		数据库操作文件      
|---dome.php		测试文件         


#### 调用
Ddic/File/PdoSql 类使用时：实例化前传参数数组形式； 实例化后传参对象形式；   
例如：  
      `$config['fileExt']='csv'; 
       $Ddic=new Ddic($config);
       $Ddic->fileExt='csv';` 
      
结果示例：  
![示例单个文件](./src/test/test_file.png)
![多个文件](./src/test/test_files.png)