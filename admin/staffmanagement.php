
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "staffmanagement";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle create staff action
if (isset($_POST['create'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Not hashed
    $phonenumber = $conn->real_escape_string($_POST['phonenumber']);
    
    // Password policy: at least 8 characters, including one uppercase letter, one number, and one special character
    if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.');</script>";
    } else {
        // If the password meets the criteria, proceed with the database insertion
        $sql = "INSERT INTO staff (username, emailstaff, PASSWORD, phonenumber) VALUES ('$username', '$email', '$password', '$phonenumber')";
        
        if (!$conn->query($sql)) {
            die("Error creating staff: " . $conn->error);
        } else {
            echo "<script>alert('Staff created successfully');</script>";
        }
    }
}

// Handle delete staff action
if (isset($_POST['delete'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $sql = "DELETE FROM staff WHERE username='$username'";
    
    if (!$conn->query($sql)) {
        die("Error deleting staff: " . $conn->error);
    } else {
        echo "<script>alert('Staff deleted successfully');</script>";
    }
}

// Handle update staff action
if (isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['edit_username']);
    $email = $conn->real_escape_string($_POST['edit_email']);
    $phonenumber = $conn->real_escape_string($_POST['edit_phonenumber']);
    
    $sql = "UPDATE staff SET emailstaff='$email', phonenumber='$phonenumber' WHERE username='$username'";
    
    if (!$conn->query($sql)) {
        die("Error updating staff: " . $conn->error);
    } else {
        echo "<script>alert('Staff updated successfully');</script>";
    }
}

// Handle search staff action
$searchResult = null;
if (isset($_POST['search'])) {
    $searchUsername = $conn->real_escape_string($_POST['search_username']);
    $searchSql = "SELECT username, emailstaff, PASSWORD, phonenumber FROM staff WHERE username LIKE '%$searchUsername%'";
    $searchResult = $conn->query($searchSql);
}

// Fetching staff data
$sql = "SELECT username, emailstaff, PASSWORD, phonenumber FROM staff";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <style>
        body {
            background-image: url('https://www.fouroakshealthcare.co.uk/wp-content/uploads/2022/07/iStock-1380983332-1170x740.jpg');
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #006064;
            margin-top: 20px;
        }
        form {
            margin: 20px auto;
            max-width: 400px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #00796b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004d40;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            background-color: rgba(255, 255, 255, 0.8);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #009688;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Manage Staff Accounts</h1>

    <!-- Form for creating a new staff account -->
    <form action="staffmanagement.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password (8+ chars, uppercase, number, special char)" required><br>
        <input type="text" name="phonenumber" placeholder="Phone Number" required><br>
        <button type="submit" name="create">Create Staff</button>
    </form>

    <!-- Form for searching a staff account -->
    <form action="staffmanagement.php" method="POST">
        <input type="text" name="search_username" placeholder="Search by Username" required><br>
        <button type="submit" name="search">Search</button>
    </form>

    <hr>

    <!-- Display search results -->
    <?php if ($searchResult && $searchResult->num_rows > 0): ?>
        <h2>Search Results:</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Password</th>
            </tr>
            <?php while ($row = $searchResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['emailstaff']); ?></td>
                <td><?php echo htmlspecialchars($row['phonenumber']); ?></td>
                <td><?php echo htmlspecialchars($row['PASSWORD']); ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php elseif (isset($_POST['search'])): ?>
        <p>No results found for "<?php echo htmlspecialchars($searchUsername); ?>"</p>
    <?php endif; ?>

    <hr>

    
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Password</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['emailstaff']); ?></td>
            <td><?php echo htmlspecialchars($row['phonenumber']); ?></td>
            <td><?php echo htmlspecialchars($row['PASSWORD']); ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
<?php
$conn->close();
?>
