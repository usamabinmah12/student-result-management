<?php
include "db.php";


$students = [
    ['2024001', 'John Doe', 'student@example.com', 'Computer Science'],
    ['2024002', 'Jane Smith', 'jane@example.com', 'Electrical Engineering'],
    ['2024003', 'Bob Wilson', 'bob@example.com', 'Mechanical Engineering'],
    ['2024004', 'Alice Brown', 'alice@example.com', 'Civil Engineering'],
    ['2024005', 'Charlie Lee', 'charlie@example.com', 'Business Administration']
];

foreach ($students as $student) {
    $id = $student[0];
    $name = $student[1];
    $email = $student[2];
    $dept = $student[3];
    
    
    $check = $conn->prepare("SELECT id FROM students WHERE id=?");
    $check->bind_param("s", $id);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO students (id, name, email, department) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $name, $email, $dept);
        
        if ($stmt->execute()) {
            echo "Added: $name ($email)<br>";
        } else {
            echo "Error adding: $name - " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Student already exists: $name ($email)<br>";
    }
    $check->close();
}

$conn->close();
?>