<?php header("HTTP/1.0 500 Internal Server Error"); ?>
<h2>500 Internal Server Error</h2>
<?php if (!empty($message)): ?>
<p><?php echo esc($message); ?></p>
<?php endif; ?>
<?php if (QF_DEBUG && !empty($exception)): ?>
<pre>
<?php echo $exception; ?>
</pre>
<?php endif; ?>