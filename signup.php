<?php
// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if (isset($_POST['signup'])) {
    $name = sanitize_input($_POST['name']);
    $password = sanitize_input($_POST['password']);
    $email = sanitize_input($_POST['email']);
    $location = sanitize_input($_POST['location']);

    // Validate the form data
    $errors = array();
    
    // Name validation: It should not be empty
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    // Password validation: It should not be empty and should have at least 6 characters
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password should have at least 6 characters";
    }

    // Email validation: It should not be empty and should be a valid email address
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Location validation: It should not be empty
    if (empty($location)) {
        $errors[] = "Location is required";
    }

    // If there are no validation errors, insert the data into the database
    if (empty($errors)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (name, password, email, location) VALUES (?, ?, ?, ?)");

        // Bind the parameters and execute the statement
        $stmt->bind_param("ssss", $name, $password, $email, $location);
        $stmt->execute();

        // Display a success message
        echo "Signup successful! Welcome, $name!";
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>