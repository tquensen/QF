<?php header("HTTP/1.0 403 Forbidden"); ?>
<?php if (QF_DEBUG && !empty($exception)): ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 403, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception)); ?>
<?php else: ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 403, 'message' => !empty($message) ? $message : '')); ?>
<?php endif; ?>