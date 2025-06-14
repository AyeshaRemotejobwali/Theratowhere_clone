<?php
session_start();
require_once 'db.php';
$articles = $conn->query("SELECT * FROM articles")->fetch_all(MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_article'])) {
    $user_id = $_SESSION['user_id'];
    $article_id = $_POST['article_id'];
    $stmt = $conn->prepare("INSERT INTO saved_articles (user_id, article_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $article_id);
    $stmt->execute();
    echo "<script>alert('Article saved!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guides</title>
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
        .blog-container {
            padding: 50px;
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .article-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 15px;
        }
        .article-card h3 {
            margin-bottom: 10px;
        }
        .article-card p {
            color: #666;
        }
        .article-card button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .article-card button:hover {
            background: #2980b9;
        }
        @media (max-width: 768px) {
            .blog-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Travel Guides & Blog</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('search.php')">Search Trips</a>
            <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
        </nav>
    </header>
    <section class="blog-container">
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                <p><?php echo htmlspecialchars($article['content']); ?></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST">
                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                        <button type="submit" name="save_article">Save Article</button>
                    </form>
                <?php else: ?>
                    <p><a iniziativa="redirect('login.php')">Login to save</a></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
