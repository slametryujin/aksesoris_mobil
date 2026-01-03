<?php
session_start();
include '../config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Aksesoris Mobil</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
	<h2>Produk Kami</h2>

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

	<form method="get" style="margin-bottom:12px;display:flex;gap:8px;align-items:center">
		<input type="text" name="q" placeholder="Cari produk..." value="<?= htmlspecialchars($q_search); ?>">
		<select name="kategori">
			<option value="0">Semua Kategori</option>
			<?php foreach($kategori_list as $kc){
				$sel = ($filter_kategori==$kc['id']) ? 'selected' : '';
				echo '<option value="'.$kc['id'].'" '.$sel.'>'.htmlspecialchars($kc['nama']).'</option>';
			} ?>
		</select>
		<button type="submit">Cari</button>
	</form>

	<div class="produk">
	<?php while($p = mysqli_fetch_assoc($res)){
	?>
		<div class="card">
			<a href="product.php?id=<?= $p['id']; ?>"><img src="/aksesoris_mobil/assets/img/<?= htmlspecialchars($p['gambar']); ?>" alt="<?= htmlspecialchars($p['nama_produk']); ?>"></a>
			<h3><?= htmlspecialchars($p['nama_produk']); ?></h3>
			<p class="text-muted"><?= substr($p['deskripsi'],0,80); ?>...</p>
			<b>Rp <?= number_format($p['harga']); ?></b>
		</div>
	<?php } ?>
	</div>

	<!-- pagination -->
	<div style="margin-top:16px">
		<?php if($total > $perPage){
			$base = strtok($_SERVER["REQUEST_URI"], '?');
			// build query params excluding page
			$params = $_GET; unset($params['page']);
			for($pnum=1;$pnum<=$totalPages;$pnum++){
				$params['page']=$pnum;
				$qs = http_build_query($params);
				$active = ($pnum==$page) ? 'font-weight:bold' : '';
				echo '<a href="'.$base.'?'.$qs.'" style="margin-right:8px;'.$active.'">'.$pnum.'</a>';
			}
		} ?>
	</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>