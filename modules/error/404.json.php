<?php header("HTTP/1.0 404 Not Found"); ?>
<?php if (QF_DEBUG && !empty($exception)): ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 404, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception)); ?>
<?php else: ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 404, 'message' => !empty($message) ? $message : '')); ?>
<?php endif; ?>