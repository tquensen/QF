<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>My Website</title>
  </head>
  <body class="<?php echo 'module-'.qf_config('current_module', 'unknown').' page-'.qf_config('current_module', 'unknown').'-'.qf_config('current_page', 'unknown'); ?>">
      <h1>QF</h1>
    <?php echo $content; ?>
  </body>
</html>
