<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data['tit'];  ?></title>
    <style>
        body {
            color:#4f6b72;
            padding:0;
            margin:0;
            background:#fff; font-size:14px;
            font-family:12px/1.5 tahoma,arial,'Hiragino Sans GB',\5b8b\4f53,sans-serif; line-height:150%; padding-bottom:20px;color:#404040; line-height:30px;
        }
        div{margin:0px; padding: 0px;}
        a,a:visited {
            text-decoration:none;
            color:#4f6b72;
            border-bottom:1px dotted #CCC;
        }

        a:hover {
            text-decoration:underline;
        }

        table {
            border:1px solid #C1DAD7;
            border-collapse:collapse;
        }
        .table_con{margin-bottom: 40px;}

        th {
            padding:5px;
        }
        table th:nth-of-type(1){ width:10%;}
        table th:nth-of-type(2){ width:20%;}
        table th:nth-of-type(3){ width:20%;}
        table th:nth-of-type(4){ width:20%;}
        td {
            padding:3px 5px 3px 10px;
            vertical-align:top;
        }

        .menu {
            background:#eee;
            height:38px; line-height:38px;
            width:100%;
            padding:0 30px; position: fixed;top:0px; z-index: 10; border-bottom: 1px solid #DAE0E4;
            box-shadow: 0 1px 2px 0 rgba(158,172,182,.2);
        }

        .menu h3 {
            float:left;
            font-size:16px;
            margin:0;
            padding:0;
        }

        .menu a,
        .menu a:visited {
            font-size:12px;
            text-decoration:none;
            color:#666;
            padding:5px 10px;
            height:20px;
            background:#FFF;
            border:1px solid #FFF;
        }

        .menu a:hover {
            background:transparent;
        }

        .content {
            padding:10px 30px; margin-top: 40px;
        }

        /*模块标题*/
        textarea{border:1px solid #ccc; width:938px; padding:10px; height:100px;}
        .table_tit th{border:none !important;background:#d6eef0; font-size: 15px;}
        td{text-align: center;}
        h1{ height:40px; line-height:40px; background:#4f81bd; color:#FFF; padding-left:20px; font-size:16px; margin-top:60px; }
        ul{ border:1px solid #FFCC00; padding:5px 10px;}
        ul strong{ color:#FF0000}
        .share a{ text-decoration:none; border:none}
        .share  dl{ float:left; line-height:22px; padding-right:10px;}
        .share div{ float:left; padding-top:11px;}
    </style>
    <!--<link href="./file/style.css" rel="stylesheet" type="text/css" media="all" />-->
</head>
<body>
    <div class="menu">
    <h3><?php echo $data['head']['dbname'].' 数据库 &nbsp;  &nbsp;  字符：'. $data['head']["charset"]."  &nbsp;  &nbsp;  ".$data['head']["table_info"].$data['head']["table_c"]?></h3>
    </div>
    <div class="content">
        <?php foreach($data['body']['tit'] as $tk=>$tv) { ?> <!--表信息-->
          <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <tr class="table_tit">
                <th><?php echo  $tv['index']; ?></th>
                <th><?php echo  $tv["Name"].'( '.$tv["Comment"].' )'; ?></th>
                <th><?php echo  $tv["Collation"]; ?></th>
                <th><?php echo  $tv["Engine"]; ?></th>
                <th><?php echo  $tv["Field"]; ?></th>
                <th><?php echo  $tv["Rows"]; ?></th>
            </tr>
          </table>
          <table width="100%" border="1" cellpadding="0" cellspacing="0" class="table_con">
            <tr style="background:#F1F1F1;">
                <?php foreach($data['fieldTitKey'] as $fk=>$fv) { ?> <!--表字段说明-->
                <th style="white-space:nowrap;min-width:100px; max-width: 200px;width:auto"><?php echo  $fv; ?></th>
                <?php }; ?>
            </tr>

            <?php foreach($data['body']['body'][$tk][$tv["Name"]] as $bk=>$bv) {?>  <!--表字段信息-->
                <tr>
                    <?php foreach($data['fieldTitVal'] as $fk=>$fv) { ?> <!--表字段信息说明-->
                        <td><?php echo $bv[$fv]; ?></td>
                    <?php }; ?>
                </tr>
            <?php }; ?>
        </table>
        <?php }; ?>
    </div>

</body>
</html>