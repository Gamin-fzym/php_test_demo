<?php
$host = '127.0.0.1';
$db = 'city_database';
$user = 'gamin';
$pass = '123456';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 连接数据库
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    createCitiesTable();
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// 创建城市表
function createCitiesTable() {
    $tb_name = "cities";
    // 检查表是否已存在
    $isTableExists = checkTableExists($tb_name);
    if (!$isTableExists) { 
        // 创建表
        $sql = "CREATE TABLE $tb_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code INT NOT NULL
        )";
        global $pdo;
        $pdo->exec($sql);
    } else {
         
    }
}

// 检查表是否存在
function checkTableExists($tableName) {
    global $pdo;
    $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
    return $stmt->rowCount() > 0;
}

?>