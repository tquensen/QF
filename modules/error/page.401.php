<?php header("HTTP/1.0 401 Unauthorized"); ?>
<h2>401 Unauthorized</h2>
<?php if (!empty($message)): ?>
<p><?php echo esc($message); ?></p>
<?php endif; ?>