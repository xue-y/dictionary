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
        table th:nth-of-type(2){ width:30%;}
        table th:nth-of-type(3){ width:15%;}
        table th:nth-of-type(4){ width:15%;}
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
        #tbox{width:58px; float:right; position:fixed; right:50px; bottom:15px;
            _position:absolute;
            _bottom:auto;
            _top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)));
            _margin-bottom:15px;
        }/*解决IE6下不兼容 position:fixed 的问题*/
        #gotop{ width:58px; height:56px; display:block;}
        #gotop{ background-position:0 -118px;}
        #gotop:hover{ background-position:0 -59px;}
        .a{ display:block; height:30px; line-height:30px; background:#2d96e9; text-decoration: none; text-align: center; color:#FFFFFF; border-bottom:2px solid #fff}
        .a:hover{ background:#4f81bd}
        .b{ background:#4f81bd  }
        .blank{height:10px; line-height:10px; clear:both; visibility:hidden;}
        .share { float:left}
        .share a{ text-decoration:none; border:none}
        .share  dl{ float:left; line-height:22px; padding-right:10px;}
        .share div{ float:left; padding-top:11px;}
    </style>
    <!--<link href="./file/style.css" rel="stylesheet" type="text/css" media="all" />-->
</head>
<body>
    <div class="menu">
    <h3><?php echo $data['head']['dbname'].' 数据库; &nbsp;  字符：'. $data['head']["charset"]." ; &nbsp;  ".$data['head']["table_info"].$data['head']["table_c"]?></h3>
    </div>
    <div class="content">
        <?php foreach($data['body']['tit'] as $tk=>$tv) { ?> <!--表信息-->
          <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <tr class="table_tit">
                <th>表：<?php echo  $tk+1; ?></th>
                <th><?php echo  $tv["Name"]; ?></th>
                <th><?php echo  $tv["Collation"]; ?></th>
                <th><?php echo  $tv["Engine"]; ?></th>
                <th><?php echo  $tv["Comment"]; ?></th>
            </tr>
          </table>
          <table width="100%" border="1" cellpadding="0" cellspacing="0" class="table_con">
            <tr style="background:#F1F1F1;">
                <?php foreach($data['fieldTitKey'] as $fk=>$fv) { ?> <!--表字段说明-->
                <th><?php echo  $fv; ?></th>
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