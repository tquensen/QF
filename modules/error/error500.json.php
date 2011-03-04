<?php header("HTTP/1.0 500 Internal Server Error");
if (QF_DEBUG && !empty($exception)):
    echo json_encode(array('success' => false, 'errorCode' => 500, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception));
else:
    echo json_encode(array('success' => false, 'errorCode' => 500, 'message' => !empty($message) ? $message : ''));
endif;