<?php
// api_laporan_transaksi.php - VERSI FINAL & PASTI BERHASIL
include '../Koneksi2.php';

ob_start(); // Jaga-jaga agar tidak ada output sebelum JSON

$response = [];

// 1. Detail transaksi (untuk expand row)
if (isset($_GET['detail_id'])) {
    $transaksi_id = mysqli_real_escape_string($koneksi, $_GET['detail_id']);
    $query = "SELECT 
                m.nama_menu AS name,
                d.qty,
                d.harga,
                d.subtotal
              FROM detail_transaksi d
              JOIN menu m ON d.menu_id = m.id
              WHERE d.transaksi_id = '$transaksi_id'";
    $result = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
    }

// 2. Export CSV (opsional)
} elseif (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $query = "SELECT 
                d.transaksi_id,
                p.nama_pelanggan AS nama_customer,
                d.tanggal_transaksi,
                SUM(d.subtotal) AS total_harga,
                d.status
              FROM detail_transaksi d
              JOIN pelanggan p ON d.pelanggan_id = p.pelanggan_id
              GROUP BY d.transaksi_id
              ORDER BY d.tanggal_transaksi DESC";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
    }

// 3. Chart pendapatan
} elseif (isset($_GET['chart']) && $_GET['chart'] === 'pendapatan') {
    $query = "SELECT 
                DATE(tanggal_transaksi) AS tanggal,
                SUM(subtotal) AS pendapatan
              FROM detail_transaksi
              WHERE status = 'selesai'
              GROUP BY DATE(tanggal_transaksi)
              ORDER BY tanggal DESC
              LIMIT 7";
    $result = mysqli_query($koneksi, $query);
    $labels = [];
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = date('d/m', strtotime($row['tanggal']));
        $data[] = (float)$row['pendapatan'];
    }
    $response = ['labels' => array_reverse($labels), 'data' => array_reverse($data)];

// 4. Data utama untuk DataTables
} else {
    $draw = intval($_GET['draw'] ?? 1);
    $start = intval($_GET['start'] ?? 0);
    $length = intval($_GET['length'] ?? 10);

    // Query utama - tanpa filter dulu untuk test
    $query = "SELECT 
                d.transaksi_id,
                p.nama_pelanggan AS nama_customer,
                MAX(d.tanggal_transaksi) AS tanggal_transaksi,
                SUM(d.subtotal) AS total_harga,
                d.status
              FROM detail_transaksi d
              JOIN pelanggan p ON d.pelanggan_id = p.pelanggan_id
              GROUP BY d.transaksi_id, d.status
              ORDER BY MAX(d.tanggal_transaksi) DESC
              LIMIT $start, $length";

    $result = mysqli_query($koneksi, $query);

    // Hitung total data (untuk pagination)
    $totalQuery = "SELECT COUNT(DISTINCT transaksi_id) as total FROM detail_transaksi";
    $totalResult = mysqli_query($koneksi, $totalQuery);
    $totalRecords = mysqli_fetch_assoc($totalResult)['total'];

    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id_transaksi' => $row['transaksi_id'],
                'nama_customer' => $row['nama_customer'],
                'tanggal_transaksi' => date('d/m/Y H:i', strtotime($row['tanggal_transaksi'])),
                'total_harga' => $row['total_harga'],
                'status' => $row['status']
            ];
        }
    }

    $response = [
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $data
    ];
}

// Output JSON bersih
ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>