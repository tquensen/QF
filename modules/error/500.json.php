<?php header("HTTP/1.0 500 Internal Server Error"); ?>
<?php if (QF_DEBUG && !empty($exception)): ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 500, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception)); ?>
<?php else: ?>
    <?php echo json_encode(array('success' => false, 'errorCode' => 500, 'message' => !empty($message) ? $message : '')); ?>
<?php endif; ?>