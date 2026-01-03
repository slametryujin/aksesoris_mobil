<?php
session_start();
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <h2>Koleksi Aksesoris Mobil Terbaik</h2>
    <p>Tingkatkan gaya dan kenyamanan mobil Anda dengan aksesoris berkualitas tinggi dari koleksi kami</p>
  </div>
</section>

<div class="container">
  <!-- Page Header -->
  <div class="page-header">
    <h2>Produk Kami</h2>
    <p>Temukan berbagai aksesoris mobil berkualitas untuk kebutuhan Anda</p>
  </div>

  <?php
  // fetch categories for filter
  $cats = mysqli_query($conn,"SELECT * FROM kategori ORDER BY nama");
  $kategori_list = [];
  while($c = mysqli_fetch_assoc($cats)) $kategori_list[] = $c;

  // filters: search q, kategori, pagination
  $q_search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
  $filter_kategori = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
  $page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
  $perPage = 8;

  // build where clause
  $where = 'WHERE 1=1';
  if($filter_kategori){ $where .= ' AND kategori_id=' . $filter_kategori; }
  if($q_search){
    $where .= " AND (nama_produk LIKE '%$q_search%' OR deskripsi LIKE '%$q_search%')";
  }

  // count total
  $countRes = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk $where");
  $total = 0;
  if($countRes){ $r = mysqli_fetch_assoc($countRes); $total = intval($r['total']); }
  $totalPages = max(1, ceil($total / $perPage));
  if($page > $totalPages) $page = $totalPages;
  $offset = ($page - 1) * $perPage;

  $sql = "SELECT * FROM produk $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
  $res = mysqli_query($conn, $sql);
  ?>

  <!-- Search & Filter -->
  <div class="search-filter">
    <form method="get" style="margin-bottom:0">
      <div class="form-group">
        <input type="text" name="q" placeholder="Cari produk..." value="<?= htmlspecialchars($q_search); ?>">
      </div>
      <div class="form-group">
        <select name="kategori">
          <option value="0">Semua Kategori</option>
          <?php foreach($kategori_list as $kc){
            $sel = ($filter_kategori==$kc['id']) ? 'selected' : '';
            echo '<option value="'.$kc['id'].'" '.$sel.'>'.htmlspecialchars($kc['nama']).'</option>';
          } ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Cari</button>
    </form>
  </div>

  <!-- Product Grid -->
  <div class="produk">
    <?php if(mysqli_num_rows($res) == 0): ?>
      <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--gray-600);">
        <p style="font-size: 18px;">Tidak ada produk ditemukan.</p>
        <a href="/aksesoris_mobil/public" class="btn btn-secondary mt-2">Lihat Semua Produk</a>
      </div>
    <?php else: ?>
      <?php while($p = mysqli_fetch_assoc($res)){ ?>
        <div class="card">
          <div class="card-image-wrapper">
            <a href="product.php?id=<?= $p['id']; ?>">
              <img src="/aksesoris_mobil/assets/img/<?= htmlspecialchars($p['gambar']); ?>" alt="<?= htmlspecialchars($p['nama_produk']); ?>">
            </a>
          </div>
          <div class="card-body">
            <h3><a href="product.php?id=<?= $p['id']; ?>"><?= htmlspecialchars($p['nama_produk']); ?></a></h3>
            <p class="text-muted"><?= substr($p['deskripsi'],0,100); ?>...</p>
            <div class="card-price">Rp <?= number_format($p['harga']); ?></div>
            <a href="product.php?id=<?= $p['id']; ?>" class="btn btn-primary btn-sm btn-block">Lihat Detail</a>
          </div>
        </div>
      <?php } ?>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if($total > $perPage): ?>
    <div class="pagination">
      <?php
      $base = strtok($_SERVER["REQUEST_URI"], '?');
      // build query params excluding page
      $params = $_GET; unset($params['page']);
      for($pnum=1;$pnum<=$totalPages;$pnum++){
        $params['page']=$pnum;
        $qs = http_build_query($params);
        if($pnum == $page) {
          echo '<span class="active">'.$pnum.'</span>';
        } else {
          echo '<a href="'.$base.'?'.$qs.'">'.$pnum.'</a>';
        }
      }
      ?>
    </div>
  <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

