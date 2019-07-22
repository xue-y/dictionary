### 项目说明
通过配置 MySql 数据库信息，使用 PHP 生成数据表字典
可以输出在当前页面，可以生成文件保存在指定位置，也可以下载    
格式支持网页HTML格式、CSV格式（Excel 读取）、ZIP压缩格式      
数据库类型 MySql ,数据库连接方式使用 PDO , PHP 版本建议 5.5 以上  
生成的文件名可以自定义，支持中文，字符转换编码使用 `iconv` 函数，无法转换的字符将忽略（为空）      
如果是其他编码可以通过配置文件中`locationChar` `webChar`配置  
如果是生成文件保存到指定目录，输出的是数据文件目录地址，可以通过 `File` 类中的 `outFile()` 函数修改输出你自己想要的信息  
支持分卷，限制每个文件写入多少张表，防止数据表过多文件打开时响应时间过长  
错误日志存放位置可通过修改配置项设置，`File` 类文件 `config['logFile']` 属性自定义

**注意：**  <br/>
- 如果生成的 csv 文件中的数据是中文的，2007 版本的 office/Excel 打开乱码，高版本没有问题
- 配置项中 压缩、下载、分卷在 `fileType=>echo` 是忽略的
- PHP 需要开启 `mb` 扩展   
- 数据库字典文件的title 可在 `Ddit` 类中 `docTit()` 自定义设置，默认 `dbName."数据库字典"`

### 相比 2.0.2 版本
**调整**<br/>
- 函数注释标准化
- 修改说明文档
- 调整 `File` 类文件函数

**修复bug**<br/>
- PHP 7压缩文件中文文件名失败

### 文件说明
src/     
|---docfile/		创建文件存放目录,可以通过配置自定义；**必须有写、创建文件的权限**<br/>
|---file/			HTML 模板文件与样式文件,可以通过配置自定义；**必须有读写文件的权限**<br/>
|---log/			日志文件夹,可以通过配置自定义；**必须有写、创建文件的权限**<br/>
|---test/			示例图片目录       
|---.gitignore		GitHub 忽略文件      
|---Config.php		配置文件     
|---Ddic.php		生成数据字典核心类     
|---Derror.php		错误处理类文件     
|---File.php		生成 HTML/CSV 数据文件    
|---PdoSql.php		数据库操作文件      
|---dome.php		测试文件     
<br/>

### 调用示例
Ddic/File/PdoSql 类使用时：实例化前传参数数组形式； 实例化后传参对象形式；   
例如：  
      `$config['fileExt']='csv'; 
       $Ddic=new Ddic($config);
       $Ddic->fileExt='csv';` 
      
结果示例：  
![示例单个文件](./src/test/test_file.png)
![多个文件](./src/test/test_files.png)