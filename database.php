<?php
// Start the session
session_start();

// Validate and sanitize inputs
$school_year = isset($_POST['school_year']) ? intval($_POST['school_year']) : null;
$surname = isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : '';
$given_name = isset($_POST['given_name']) ? htmlspecialchars($_POST['given_name']) : '';
$middle_name = isset($_POST['middle_name']) ? htmlspecialchars($_POST['middle_name']) : '';
$contact_no = isset($_POST['contact_no']) ? htmlspecialchars($_POST['contact_no']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';

if (!$school_year || !$surname || !$given_name || !$middle_name || !$contact_no || !$email) {
    die('Please fill in all required fields.');
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'dataadmission');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO admission(school_year, surname, given_name, middle_name, contact_no, email) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $school_year, $surname, $given_name, $middle_name, $contact_no, $email);

// Execute statement
if ($stmt->execute()) {
    echo '<a href="display.php?surname=' . $surname . '&given_name=' . $given_name . '&middle_name=' . $middle_name . '&email=' . $email . '">' . $email . '</a><br>';
    echo '<span>Click this email to complete your registration process</span>';
} else {
    die('Execution Failed: ' . $stmt->error);
}

// Close connections
$stmt->close();
$conn->close();
?>
