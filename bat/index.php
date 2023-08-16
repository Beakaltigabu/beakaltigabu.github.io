<?php

// Get input data
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['Email'];
$service = $_POST['find-job-location'];
$date = $_POST['date'];


// Database credentials
$dbHost = 'uniquespaet.com';
$dbName = 'uniqueoj_Booking';
$dbUser = 'uniqueoj_uniqueoj';
$dbPass = 'greaterfuture';

// Connect to database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check for duplicate bookings
$sql = "SELECT id FROM bookings WHERE name='" . $conn->real_escape_string($name) . "' AND phone='" . $conn->real_escape_string($phone) . "' AND date='" . $conn->real_escape_string($date) . "'";

$result = $conn->query($sql);

if (!$result) {
  die("Error in query: " . $conn->error);
}

// Fetch bookings
$bookings = array();
while($row = $result->fetch_assoc()) {
  $bookings[] = $row;
}

if(count($bookings) > 0) {
  echo "<script>alert('Booking exists!');</script>";
  exit();
}

// Insert new booking
$stmt = $conn->prepare("INSERT INTO bookings (name, phone, email, service, date) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssss", $name, $phone, $email, $service, $date);

if(!$stmt->execute()) {
  echo "Error: " . $stmt->error;
} else {

  // Success, display message
  echo "<script>alert('Booked successfully!');</script>";

  // Redirect after 0.5 seconds
  header("Refresh:0.5; url=http://uniquespaet.com/contacts.html");
  exit();
}

?>
