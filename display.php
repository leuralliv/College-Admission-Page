<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "dataadmission";

$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success_message = "";
$error_message = "";

if (isset($_GET['surname'], $_GET['given_name'], $_GET['email'])) {
    $surname = mysqli_real_escape_string($conn, $_GET['surname']);
    $given_name = mysqli_real_escape_string($conn, $_GET['given_name']);
    $middle_name = mysqli_real_escape_string($conn, $_GET['middle_name']);
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    // Get the last inserted ID from the idnumber table
    $sql1 = "SELECT * FROM idnumber ORDER BY id_number DESC LIMIT 1";
    $result = mysqli_query($conn, $sql1);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_array($result);
        $lastid = $row['id_number'];
    } else {
        $lastid = 0;
    }

    // Generate new ID
    $newid = $lastid + 1;
    $newid = sprintf("%08d", $newid);

    // Insert new record
    $sql2 = "INSERT INTO idnumber(surname, given_name, middle_name, id_number)
             VALUES ('$surname', '$given_name', '$middle_name', '$newid')";

    if (mysqli_query($conn, $sql2)) {
        $success_message = "Registration successful!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
} else {
    $error_message = "Required parameters are missing!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT PERSONAL DATA</title>
    <link rel="stylesheet" href="formDesign.css">  <!-- Consolidate your CSS files if possible -->
</head>
<body>
    <div align="center">
        <div class="container">
            <h1>Registration Complete</h1>

            <?php if (!empty($success_message)): ?>
                <div class="info-box">
                    <p>Last ID#: <?= $lastid ? sprintf('%08d', $lastid) : 'N/A' ?></p>
                    <p>NEW ID#: <?= $newid ?> for <?= htmlspecialchars($email) ?></p>
                    <p><?= $success_message ?></p>
                </div>
            <?php elseif (!empty($error_message)): ?>
                <div class="error-box">
                    <p><?= $error_message ?></p>
                </div>
            <?php else: ?>
                <div class="error-box">
                    <p>No data available to display.</p>
                </div>
            <?php endif; ?>

            <a href="Page1.html" class="button">Back to Home</a>
        </div>
    </div>
</body>
</html>