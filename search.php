<?php
session_start();
require_once 'db.php';
$trips = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $destination = $_POST['destination'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    
    $query = "SELECT * FROM trips WHERE destination LIKE ? AND price <= ? AND type = ?";
    $stmt = $conn->prepare($query);
    $destination = "%$destination%";
    $stmt->bind_param("sds", $destination, $price, $type);
    $stmt->execute();
    $trips = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    if (isset($_POST['book'])) {
        $trip_id = $_POST['trip_id'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, trip_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $trip_id);
        if ($stmt->execute()) {
            echo "<script>alert('Trip booked successfully!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Trips</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Arial', sans-serif;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .search-container {
            padding: 50px;
            max-width: 1200px;
            margin: auto;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
        }
        button {
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        .results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .trip-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 15px;
        }
        .trip-card h3 {
            margin-bottom: 10px;
        }
        .trip-card p {
            color: #666;
        }
        .trip-card button {
            width: 100%;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            form {
                flex-direction: column;
            }
            .results {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Search Trips</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('blog.php')">Travel Guides</a>
            <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
        </nav>
    </header>
    <section class="search-container">
        <form method="POST">
            <input type="text" name="destination" placeholder="Destination" required>
            <input type="number" name="price" placeholder="Max Price" required>
            <select name="type">
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="package">Package</option>
            </select>
            <button type="submit">Search</button>
        </form>
        <div class="results">
            <?php foreach ($trips as $trip): ?>
                <div class="trip-card">
                    <h3><?php echo htmlspecialchars($trip['destination']); ?></h3>
                    <p>Price: $<?php echo $trip['price']; ?></p>
                    <p>Type: <?php echo $trip['type']; ?></p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST">
                            <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
                            <button type="submit" name="book">Book Now</button>
                        </form>
                    <?php else: ?>
                        <p><a href="#" onclick="redirect('login.php')">Login to book</a></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
