<?php header("HTTP/1.0 401 Unauthorized");
if (QF_DEBUG && !empty($exception)):
    echo json_encode(array('success' => false, 'errorCode' => 401, 'message' => !empty($message) ? $message : '', 'exception' => (string) $exception));
else:
    echo json_encode(array('success' => false, 'errorCode' => 401, 'message' => !empty($message) ? $message : ''));
endif;