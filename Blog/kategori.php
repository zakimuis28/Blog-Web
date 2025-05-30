<?php 
session_start();
include 'koneksi.php';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$where = '';
if ($kategori) {
    $where = "WHERE a.id IN (SELECT ac.article_id FROM article_category ac JOIN category c ON ac.category_id = c.id WHERE c.name = '$kategori')";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Article</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar" data-aos="fade-down">
        <div class="navbar-container">
            <a href="index.php" class="nav-logo">Gen Z Blog</a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-dropdown">
                    <a href="#" class="nav-link">Kategori</a>
                    <ul class="dropdown-menu">
                        <?php 
                        $catres = mysqli_query($conn, "SELECT * FROM category ORDER BY name ASC");
                        while($cat = mysqli_fetch_assoc($catres)) {
                            echo '<li><a href="kategori.php?kategori=' . urlencode($cat['name']) . '" class="dropdown-item">' . htmlspecialchars($cat['name']) . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><span class="nav-link" style="color:#f9d923;cursor:default;">👤 <?php echo htmlspecialchars($_SESSION['nickname']); ?></span></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link">Login</a></li>
                    <li><a href="register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <header class="header" data-aos="fade-down">
        <h1>Kategori: <?php echo htmlspecialchars($kategori); ?></h1>
        <p class="subtitle">Daftar artikel untuk kategori ini</p>
    </header>
    <main class="main-content">
        <?php 
        $query = "SELECT * FROM article a $where ORDER BY date DESC";
        $result = mysqli_query($conn, $query);
        $ada = false;
        while($row = mysqli_fetch_assoc($result)) {
            $ada = true;
            echo '<article class="post" data-aos="fade-up">';
            if (!empty($row['picture'])) {
                echo '<img src="images/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['title']) . '" class="post-img" style="width:100%;max-height:400px;object-fit:cover;border-radius:16px 16px 0 0;margin-bottom:1rem;">';
            }
            echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
            echo '<p class="date">' . htmlspecialchars($row['date']);
            // Ambil penulis
            $authorQ = mysqli_query($conn, "SELECT au.nickname FROM author au JOIN article_author aa ON au.id = aa.author_id WHERE aa.article_id = " . intval($row['id']) . " LIMIT 1");
            $hasAuthor = false;
            if($author = mysqli_fetch_assoc($authorQ)) {
                echo ' &bull; <span class="author">' . htmlspecialchars($author['nickname']) . '</span>';
                $hasAuthor = true;
            }
            // Ambil semua kategori, lalu tampilkan sekaligus
            $catQ = mysqli_query($conn, "SELECT c.name FROM category c JOIN article_category ac ON c.id = ac.category_id WHERE ac.article_id = " . intval($row['id']));
            $catArr = [];
            while($cat = mysqli_fetch_assoc($catQ)) {
                $catArr[] = '<span class="category">' . htmlspecialchars($cat['name']) . '</span>';
            }
            $catArr = array_unique($catArr);
            if (count($catArr) > 0) {
                if ($hasAuthor) {
                    echo ' &bull; ';
                } else {
                    echo ' &bull; ';
                }
                echo implode(' ', $catArr);
            }
            echo '</p>';
            echo '<div>';
            $plain = strip_tags($row['content']);
            $ringkas = mb_strimwidth($plain, 0, 200, '...');
            echo htmlspecialchars($ringkas);
            echo '</div>';
            echo '<a href="detail.php?id=' . $row['id'] . '" class="read-more-btn">Baca selanjutnya</a>';
            echo '</article>';
        }
        if (!$ada) {
            echo '<div style="text-align:center;color:#e94560;font-size:1.2rem;margin:2rem 0;">Belum ada artikel di kategori ini.</div>';
        }
        ?>
    </main>
    <footer class="footer" data-aos="fade-up">
        <p>&copy; 2025 Gen Z Blog. All rights reserved.</p>
    </footer>
    <script>
      AOS.init({ duration: 1000, once: true });
      // Dropdown kategori mobile
      document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.querySelector('.nav-dropdown > .nav-link');
        if(dropdown) {
          dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            var parent = this.parentElement;
            parent.classList.toggle('active');
          });
        }
      });
    </script>
</body>
</html>
