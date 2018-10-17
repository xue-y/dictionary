### 项目说明
通过配置 MySql 数据库信息，生成数据表字典  
可以输出在当前页面，可以生成文件保存在指定位置，也可以下载    
格式支持网页HTML格式、CSV格式（Excel 读取）、ZIP压缩格式      
数据库类型 MySql ,数据库连接方式使用 PDO , PHP 版本建议 5.5 以上  
生成的文件名可以自定义，支持中文  
如果是其他编码可以通过配置文件中`locationChar` `webChar`配置  
如果是生成文件保存到指定目录，输出的是数据文件目录地址，可以通过 File 类中的 outFile() 函数修改输出你自己想要的信息  
支持分卷，限制每个文件写入多少张表，防止数据表过多文件打开时响应时间过长

**注意：**  如果生成的 csv 文件中的数据是中文的，2007 版本的 office/Excel 打开乱码，高版本没有问题
配置项中 压缩、下载、分卷在 `fileType=>echo` 是忽略的


### 相比 1.0.0 版本
**修复BUG**<br/>
- 1.0.0 中数据库配置参数只可在 `$Ddic=new Ddic()` 前以数组形式设置参数，修复1.0.0 版本数据库配置在 `$Ddic=new Ddic()` 后以对象方式设置无效问题<br/>
- 修复分卷循环判断 BUG，如果数据条为 9 条，每卷为 3 条，会分成4卷，最后一卷没有数据<br/>

**新增**<br/>
- 增加创建压缩文件是否成功判断<br/>
- 增加判断 HTML 模板文件是否存在，HTML 模板文件名必须为英文/英文+数组命名形式<br/>
- 增加 HTML 模板样式顶部固定<br/>

**更改**<br/>
- 文件输出方式划分清晰，分为echo (直接输出)，local(保存本地)，down(下载)；详细参数请查看Config.php 文件


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


### 调用示例       
Ddic/File/PdoSql 类使用时：实例化前传参数数组形式； 实例化后传参对象形式；   
例如：  
      `$config['fileExt']='csv'; 
       $Ddic=new Ddic($config);
       $Ddic->fileExt='csv';` 
      
结果示例：  
![示例单个文件](./src/test/test_file.png)
![多个文件](./src/test/test_files.png)