<?php
include 'koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$artikel = null;
if ($id > 0) {
    $q = mysqli_query($conn, "SELECT * FROM article WHERE id = $id LIMIT 1");
    $artikel = mysqli_fetch_assoc($q);
}
if (!$artikel) {
    echo '<!DOCTYPE html><html><head><title>Artikel Tidak Ditemukan</title></head><body><h2>Artikel tidak ditemukan.</h2></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artikel['title']); ?> - Gen Z Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar Gen Z -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top" data-aos="fade-down">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <img src="images/logo.png" alt="Logo" width="36" height="36" class="me-2 rounded-circle"> Gen Z Blog
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kategori
              </a>
              <ul class="dropdown-menu" aria-labelledby="kategoriDropdown">
                <?php 
                $catres = mysqli_query($conn, "SELECT * FROM category ORDER BY name ASC");
                while($cat = mysqli_fetch_assoc($catres)) {
                  echo '<li><a class="dropdown-item" href="kategori.php?kategori=' . urlencode($cat['name']) . '">' . htmlspecialchars($cat['name']) . '</a></li>';
                }
                ?>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Masuk</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php#tentangsaya">Tentang Saya</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Modal Login/Register -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title w-100 text-center" id="authModalLabel">Masuk / Daftar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs nav-justified mb-3" id="authTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTab" type="button" role="tab" aria-controls="loginTab" aria-selected="true">Login</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTab" type="button" role="tab" aria-controls="registerTab" aria-selected="false">Register</button>
              </li>
            </ul>
            <div class="tab-content" id="authTabContent">
              <div class="tab-pane fade show active" id="loginTab" role="tabpanel" aria-labelledby="login-tab">
                <form method="post" action="login.php" class="p-2">
                  <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                  <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                  <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
              </div>
              <div class="tab-pane fade" id="registerTab" role="tabpanel" aria-labelledby="register-tab">
                <form method="post" action="register.php" class="p-2">
                  <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                  <input type="text" name="namalengkap" class="form-control mb-3" placeholder="Nama Lengkap" required>
                  <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                  <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                  <input type="password" name="confirm" class="form-control mb-3" placeholder="Konfirmasi Password" required>
                  <select name="level" class="form-select mb-3" required>
                    <option value="">Pilih Level</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                  </select>
                  <button type="submit" class="btn btn-success w-100">Daftar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <main class="main-content">
        <article class="post-detail card shadow-lg border-0 p-4 my-5" data-aos="fade-up">
            <?php if (!empty($artikel['picture'])) {
                echo '<img src="images/' . htmlspecialchars($artikel['picture']) . '" alt="' . htmlspecialchars($artikel['title']) . '" class="detail-img card-img-top mb-4" style="width:100%;max-width:800px;max-height:600px;display:block;margin:auto;object-fit:cover;border-radius:24px;">';
            } ?>
            <h2 class="card-title" style="font-size:2.2rem;"><?php echo htmlspecialchars($artikel['title']); ?></h2>
            <p class="date">
                <?php echo htmlspecialchars($artikel['date']); ?>
                <?php
                $authorQ = mysqli_query($conn, "SELECT au.nickname FROM author au JOIN article_author aa ON au.id = aa.author_id WHERE aa.article_id = " . intval($artikel['id']) . " LIMIT 1");
                if($author = mysqli_fetch_assoc($authorQ)) {
                    echo ' &bull; <span class="author">' . htmlspecialchars($author['nickname']) . '</span>';
                }
                $catQ = mysqli_query($conn, "SELECT c.name FROM category c JOIN article_category ac ON c.id = ac.category_id WHERE ac.article_id = " . intval($artikel['id']));
                $catArr = [];
                while($cat = mysqli_fetch_assoc($catQ)) {
                    $catArr[] = '<span class="category">' . htmlspecialchars($cat['name']) . '</span>';
                }
                $catArr = array_unique($catArr);
                if (count($catArr) > 0) {
                    echo ' &bull; ' . implode(' ', $catArr);
                }
                ?>
            </p>
            <div style="font-size:1.15rem;line-height:1.8;">
                <?php echo $artikel['content']; ?>
            </div>
        </article>
    </main>

    <!-- Section Tentang Saya -->
    <section id="tentangsaya" class="py-5" data-aos="fade-up">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="images/logo.png" alt="Foto Profil" class="rounded-circle shadow" style="width:160px;height:160px;object-fit:cover;">
          </div>
          <div class="col-md-8">
            <h2 class="fw-bold" style="color:#f9d923;">Tentang Saya</h2>
            <p class="lead mb-2" style="color:#e94560;">Mahasiswa & Web Developer Gen Z</p>
            <p>Halo! Saya adalah web developer Gen Z yang suka membangun website modern, kreatif, dan penuh warna. Saya suka teknologi, desain UI/UX, dan selalu update tren digital terkini. <br> <b>Skill:</b> PHP, MySQL, Bootstrap, JavaScript, UI/UX, Animasi Web.</p>
            <div class="mb-2">
              <a href="mailto:emailkamu@email.com" class="btn btn-outline-warning btn-sm me-2">Email</a>
              <a href="https://instagram.com/username" class="btn btn-outline-danger btn-sm me-2" target="_blank">Instagram</a>
              <a href="https://github.com/username" class="btn btn-outline-dark btn-sm" target="_blank">GitHub</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer" data-aos="fade-up">
        <p>&copy; 2025 Gen Z Blog. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      AOS.init({ duration: 1000, once: true });
    </script>
</body>
</html>
