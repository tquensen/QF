<?php header("HTTP/1.0 403 Forbidden");
if (QF_DEBUG && !empty($exception)):
    echo json_encode(array('success' => false, 'errorCode' => 403, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception));
else:
    echo json_encode(array('success' => false, 'errorCode' => 403, 'message' => !empty($message) ? $message : ''));
endif;