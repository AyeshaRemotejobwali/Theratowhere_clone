<?php
session_start();
require_once 'db.php';
$trips = $conn->query("SELECT * FROM trips LIMIT 3")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Platform - Homepage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            color: #333;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            font-size: 2em;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            color: #3498db;
        }
        .hero {
            background: url('https://source.unsplash.com/1600x900/?travel') no-repeat center/cover;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        .hero h2 {
            font-size: 3em;
        }
        .destinations {
            padding: 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card h3 {
            padding: 15px;
            font-size: 1.5em;
        }
        .card p {
            padding: 0 15px 15px;
            color: #666;
        }
        .card button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 15px 0;
        }
        .card button:hover {
            background: #2980b9;
        }
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2em;
            }
            .destinations {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Travel Platform</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('search.php')">Search Trips</a>
            <a href="#" onclick="redirect('blog.php')">Travel Guides</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
                <a href="#" onclick="redirect('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="redirect('login.php')">Login</a>
                <a href="#" onclick="redirect('signup.php')">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <section class="hero">
        <h2>Discover Your Next Adventure</h2>
    </section>
    <section class="destinations">
        <?php foreach ($trips as $trip): ?>
            <div class="card">
                <img src="https://source.unsplash.com/300x200/?<?php echo htmlspecialchars($trip['destination']); ?>" alt="<?php echo htmlspecialchars($trip['destination']); ?>">
                <h3><?php echo htmlspecialchars($trip['destination']); ?></h3>
                <p>Price: $<?php echo $trip['price']; ?> (<?php echo ucfirst($trip['type']); ?>)</p>
                <button onclick="redirect('booking.php?trip_id=<?php echo $trip['id']; ?>')">Book Now</button>
            </div>
        <?php endforeach; ?>
    </section>
    <footer>
        <p>© 2025 Travel Platform. All rights reserved.</p>
    </footer>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
