<?php

class ErrorCode {
    const FAILURE = -1;
    const SUCCESS = 0;
    const INVALID_INPUT = 100;
    const MISSING_PARAMETER = 101;
    const DATABASE_ERROR = 200;
    const FILE_NOT_FOUND = 201;
    const METHOD_NOT_ALLOW = 405;
    const INTERNAL_SERVER_ERROR = 500;
    const DATA_NOT_FOUND = 1000;

    public static function getErrorMessage($errorCode) {
        switch ($errorCode) {
            case self::FAILURE:
                return "Operation failed.";
            case self::SUCCESS:
                return "Operation succeeded.";
            case self::INVALID_INPUT:
                return "Invalid input provided.";
            case self::MISSING_PARAMETER:
                return "Required parameter is missing.";
            case self::DATABASE_ERROR:
                return "Database error occurred.";
            case self::FILE_NOT_FOUND:
                return "File not found.";
            case self::METHOD_NOT_ALLOW:
                return "Method not allowed.";   
            case self::INTERNAL_SERVER_ERROR:
                return "Internal Server Error.";   
            case self::DATA_NOT_FOUND:
                return "No matching data found.";  
            default:
                return "Unknown error occurred.";
        }
    }
}

function outputJSON(int $errorCode, $data = []) {
    // 构建要返回的数据
    $response = [
        'code' => $errorCode,
        'message' => ErrorCode::getErrorMessage($errorCode),
        'data' => $data
     ];
    
    // 设置响应内容为 JSON 格式
    header('Content-Type: application/json');

    // 将数据转换为 JSON 字符串
    $json = json_encode($response);

    // 输出 JSON 字符串
    echo $json;
}

?>
