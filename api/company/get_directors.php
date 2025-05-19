<?php
header('Content-Type: application/json');

$cin = $_GET['cin'] ?? '';

if (!$cin) {
    echo json_encode(['success' => false, 'message' => 'CIN is required.']);
    exit;
}

// Include database connection
require_once './db_connect.php';  // Adjust path as needed

// Fetch company ID by CIN
$stmt = $conn->prepare("SELECT id FROM companies WHERE cin = ?");
$stmt->bind_param("s", $cin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Company not found.']);
    exit;
}

$company = $result->fetch_assoc();
$company_id = $company['id'];

// Fetch directors
$stmt = $conn->prepare("SELECT name, email, contact FROM directors WHERE company_id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$directors = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'directors' => $directors]);
?>
