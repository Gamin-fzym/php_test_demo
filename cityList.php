<?php
require("pdoConnect.php");
require("output.php");

// 检查POST数据
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    try {
        // 获取数据并验证
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1; // 当前页码，默认为第一页
        $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : 10; // 每页数据条数，默认为 10

        // 对页码进行有效性检查
        $page = max(1, intval($page)); ;
        // 确保每页记录数为正整数
        $pageSize = max(1, intval($pageSize)); ;

        // 表名
        $tb_name = "cities";

        // 查询总记录数
        $countSql = "SELECT COUNT(*) AS total FROM $tb_name";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute();
        $totalItems = $countStmt->fetchColumn();
        // 计算总页数
        $totalPages = ceil($totalItems / $pageSize);

        // 对页码进行有效性检查
        $page = min($page, $totalPages);
        // 计算偏移量
        $offset = ($page - 1) * $pageSize;

        // 查询当前页的数据
        $sql = "SELECT * FROM $tb_name LIMIT :offset, :pageSize";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':pageSize', $pageSize, PDO::PARAM_INT);
        $stmt->execute();

        $pagedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 构建要返回的数据
        $response = [
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'data' => array_map(function ($item) {
                return $item;
            }, $pagedData)
        ];
        outputJSON(ErrorCode::SUCCESS, $response);
    } catch (Exception $e) {
        // 发生错误时返回错误响应
        outputJSON(ErrorCode::INTERNAL_SERVER_ERROR);
    }
} else {
    // 非 POST 请求返回错误响应
    outputJSON(ErrorCode::METHOD_NOT_ALLOW);
}

?>