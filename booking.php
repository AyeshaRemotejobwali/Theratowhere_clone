<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to book a trip.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $trip_id = $_POST['trip_id'];
    
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, trip_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $trip_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Trip booked successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error booking trip. Please try again.'); window.location.href='index.php';</script>";
    }
}

if (!isset($_GET['trip_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$trip_id = $_GET['trip_id'];
$stmt = $conn->prepare("SELECT * FROM trips WHERE id = ?");
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$trip = $stmt->get_result()->fetch_assoc();

if (!$trip) {
    echo "<script>alert('Trip not found.'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trip</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .booking-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .trip-details {
            margin-bottom: 20px;
            text-align: left;
        }
        .trip-details p {
            margin: 10px 0;
            color: #666;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }
        button:hover {
            background: #2980b9;
        }
        a {
            display: block;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .booking-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Book Your Trip</h2>
        <div class="trip-details">
            <p><strong>Destination:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
            <p><strong>Price:</strong> $<?php echo $trip['price']; ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst($trip['type']); ?></p>
        </div>
        <form method="POST">
            <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
            <button type="submit">Confirm Booking</button>
        </form>
        <a href="#" onclick="redirect('index.php')">Back to Home</a>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
