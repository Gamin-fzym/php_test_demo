<?php
require "pdoConnect.php";

function pdo() {
    global $pdo;
    if (isset($pdo)) {
        return $pdo;
    } else {
        // 处理 $pdo 未初始化的情况
        return null;
    }
}

// 改变数据 插入|更新|删除
function changeData(string $sql, array $arr) {
    $stmt = pdo()?->prepare($sql);
    $result = $stmt?->execute($arr);
    return $result;
}

// 查询数据
function findData(string $sql, array $arr) {
    $stmt = pdo()?->prepare($sql);
    $stmt?->execute($arr);
    $result = $stmt?->fetch();
    return $result;
}

?>
