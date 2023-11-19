<?php
require "pdoConnect.php";
require "output.php";

$tb_name = "cities";

// 检查POST数据
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* 方式一： */
    // 获取数据并验证
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $mark = $_POST['mark'] ?? 0;

    /* 方式二：表单中Content-Type用'application/json'时，用这种方式获取传参 
    // 获取 JSON 数据并解析
    $data = json_decode(file_get_contents('php://input'), true);
    // 获取数据并验证
    $name = $data['name'] ?? '';
    $code = $data['code'] ?? '';
    $mark = $data['mark'] ?? 0;
    */

    if (empty($name) || empty($code) || $mark == 0) {
        outputJSON(ErrorCode::MISSING_PARAMETER);
        return;
    }

    // mark 1:增 2:删 3:改 4:查
    if ($mark == 1) {
        // 插入数据
        $result = changeData("INSERT INTO $tb_name (name, code) VALUES (:name, :code)", ['name' => $name, 'code' => $code]);
        if ($result) {
            outputJSON(errorCode::SUCCESS);
        } else {
            outputJSON(errorCode::FAILURE);
        }
    } else if ($mark == 2) {
        // 删除数据
        $result = changeData("DELETE FROM $tb_name WHERE code = :code", ['code' => $code]);
        if ($result) {
            outputJSON(errorCode::SUCCESS);
        } else {
            outputJSON(errorCode::FAILURE);
        }
    } else if ($mark == 3) {
        // 更新数据
        $result = changeData("UPDATE $tb_name SET name = :name WHERE code = :code", ['name' => $name, 'code' => $code]);
        if ($result) {
            outputJSON(errorCode::SUCCESS);
        } else {
            outputJSON(errorCode::FAILURE);
        }
    } else if ($mark == 4) {
        // 查询数据
        $result = findData("SELECT * FROM $tb_name WHERE code = :code", ['code' => $code]);
        if ($result) {
            // 查询成功，至少有一条匹配的数据
            outputJSON(errorCode::SUCCESS,$result);
        } else {
            // 查询失败，没有匹配的数据
            outputJSON(ErrorCode::DATA_NOT_FOUND);
        }
    }
}

// 改变数据 插入|更新|删除
function changeData(string $sql, array $arr) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($arr);
    return $result;
}

// 查询数据
function findData(string $sql, array $arr) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($arr);
    $result = $stmt->fetch();
    return $result;
}

?>