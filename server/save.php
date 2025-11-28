<?php
require './utils/db.php';
$fn = $_POST['full-name'];

try {
    $q = "INSERT INTO public.person(full_name)VALUES (:fn);";
    $stmt = $pdo->prepare($q);
    $stmt->execute(["fn" => $fn]);
    echo json_encode(
        [
            "success" => true,
            "message" => "Created O.K."
        ]
    );

} catch (PDOException $e) {
    echo json_encode(
        [
            "success" => false,
            "message" => $e->getMessage()
        ]
    );
}

?>