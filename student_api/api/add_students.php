<?php
require 'database.php';

$data = json_decode(file_get_contents("php://input"));


file_put_contents("debug_log.txt", print_r($data, true));

if (isset($data->name) && isset($data->midterm_score) && isset($data->final_score)) {
    $name = $data->name;
    $midterm = $data->midterm_score;
    $final = $data->final_score;

    $stmt = $conn->prepare("INSERT INTO students (name, midterm_score, final_score) VALUES (?, ?, ?)");

    if (!$stmt) {
        echo json_encode(["error" => "SQL error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sdd", $name, $midterm, $final);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Student added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add student", "sql_error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid input", "received_data" => $data]);
}

?>