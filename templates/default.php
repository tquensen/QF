<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $qf->page_title ? $qf->page_title . ' | ' : ''; ?><?php echo $qf->html_title; ?></title>
    </head>
    <body class="<?php echo 'module-' . $qf->getConfig('current_module', 'unknown') . ' page-' . $qf->getConfig('current_module', 'unknown') . '-' . $qf->getConfig('current_page', 'unknown'); ?>">
        <?php echo $content; ?>
    </body>
</html>
