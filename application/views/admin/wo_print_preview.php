<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/20/15
 * Time: 3:30 PM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .title_bold {
            font-weight: 600;
            font-family: Verdana, Geneva, sans-serif;
        }
        table, td {
            font-family: Verdana, Geneva, sans-serif;
        }
        th{
            font-size: 45px;
            font-family: Verdana, Geneva, sans-serif;
        }
        .table-title-td{
            font-size: 40px;
            text-align: center;
            font-family: Verdana, Geneva, sans-serif;
        }
        span, p, strong {
            font-family: Verdana, Geneva, sans-serif;
        }
    </style>
</head>
<body>
<div style="width:100%;">
        <div style="width:540px; margin:0px auto;">
        <img src="<?php echo base_url('extras/img/Kalp-Engineering-3-home.jpg');?>" width="540" />
        </div>

    <?php echo $render_template;?>
</div>
<div style="width:110px; margin:0px auto;">
<?php echo (!empty($footer)) ? $footer : '';?>
</div>