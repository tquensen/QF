<?php header("HTTP/1.0 403 Forbidden"); ?>
<h2>403 Forbidden</h2>
<?php if (!empty($message)): ?>
<p><?php echo esc($message); ?></p>
<?php endif; ?>