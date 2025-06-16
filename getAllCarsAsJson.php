<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Connect to DB
$link = mysqli_connect("awseb-e-gmxuwapfep-stack-awsebrdsdatabase-in8izjrfk3kk.cak1tr3azd8u.us-east-1.rds.amazonaws.com", "carsqt", "123456789", "ass2");
if (!$link) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit;
}

// Query all cars
$query_string = "SELECT * FROM cars";
$result = mysqli_query($link, $query_string);
if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . mysqli_error($link)]);
    exit;
}

// Fetch data and encode images
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    if (isset($row['image']) && !empty($row['image'])) {
        // Encode binary image to base64
        $row['image'] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
    } else {
        $row['image'] = null; // or you could use a placeholder string
    }
    $data[] = $row;
}

// Output JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>

