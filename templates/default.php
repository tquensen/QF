<?php
	header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html class="nojs <?php echo 'module-' . $qf->getConfig('current_module', 'unknown') . ' page-' . $qf->getConfig('current_module', 'unknown') . '-' . $qf->getConfig('current_page', 'unknown'); ?>">
    <head>
        <script>(function(H){H.className=H.className.replace(/\bnojs\b/,'js')})(document.documentElement)</script>
        <meta charset="UTF-8">
        <title><?php echo $qf->page_title ? $qf->page_title . ' | ' : ''; ?><?php echo $qf->html_title; ?></title>
    </head>
    <!--[if lt IE 7 ]> <body class="ie ie6 ltie7 ltie8 ltie9">     <![endif]-->
    <!--[if IE 7 ]>    <body class="ie ie7 ltie8 ltie9 gtie6">     <![endif]-->
    <!--[if IE 8 ]>    <body class="ie ie8 ltie9 gtie6 gtie7">     <![endif]-->
    <!--[if IE 9 ]>    <body class="ie ie9 gtie6 gtie7 gtie8">     <![endif]-->
    <!--[if !IE]><!--> <body class="noie gtie6 gtie7 gtie8">   <!--<![endif]-->
        <?php echo $content; ?>
    </body>
</html>