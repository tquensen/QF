<?php header("HTTP/1.0 404 Not Found");
if (QF_DEBUG && !empty($exception)):
    echo json_encode(array('success' => false, 'errorCode' => 404, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception));
else:
    echo json_encode(array('success' => false, 'errorCode' => 404, 'message' => !empty($message) ? $message : ''));
endif;