<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$bookings = $conn->query("SELECT b.*, t.destination, t.price, t.type FROM bookings b JOIN trips t ON b.trip_id = t.id WHERE b.user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
$saved_articles = $conn->query("SELECT a.* FROM saved_articles sa JOIN articles a ON sa.article_id = a.id WHERE sa.user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
    $trip_id = $_POST['trip_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, trip_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $trip_id, $rating, $comment);
    $stmt->execute();
    echo "<script>alert('Review submitted!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
        .dashboard-container {
            padding: 50px;
            max-width: 1200px;
            margin: auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }
        .section h3 {
            margin-bottom: 15px;
        }
        .section p {
            color: #666;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, select, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('search.php')">Search Trips</a>
            <a href="#" onclick="redirect('blog.php')">Travel Guides</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <section class="dashboard-container">
        <div class="section">
            <h3>Your Bookings</h3>
            <?php foreach ($bookings as $booking): ?>
                <p><?php echo htmlspecialchars($booking['destination']); ?> - $<?php echo $booking['price']; ?> (<?php echo $booking['type']; ?>)</p>
                <form method="POST">
                    <input type="hidden" name="trip_id" value="<?php echo $booking['trip_id']; ?>">
                    <select name="rating" required>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                    <textarea name="comment" placeholder="Your review" required></textarea>
                    <button type="submit" name="review">Submit Review</button>
                </form>
            <?php endforeach; ?>
        </div>
        <div class="section">
            <h3>Saved Articles</h3>
            <?php foreach ($saved_articles as $article): ?>
                <p><?php echo htmlspecialchars($article['title']); ?></p>
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
