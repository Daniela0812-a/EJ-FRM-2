<?php

$host = 'localhost';
$port = '5432';
$dbname = 'people';
$user = 'postgres';
$pass = 'Tumaco2025';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Conexión establecida con $dbname";
} catch (PDOException $e) {
    echo "Falló la conexión con $dbname: " . $e->getMessage();
}

?>