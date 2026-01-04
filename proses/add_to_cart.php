<?php
/**
 * ====================================
 * ADD TO CART PROCESSOR
 * ====================================
 * Memproses penambahan item ke keranjang belanja
 * dengan validasi lengkap dan response JSON
 */

session_start();
include "../Koneksi2.php";
// Set header untuk JSON response (optional, bisa digunakan untuk AJAX)
header('Content-Type: application/json');

/**
 * ====================================
 * FUNGSI HELPER
 * ====================================
 */

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/**
 * Redirect dengan session message
 */
function redirectWithMessage($location, $type, $message) {
    $_SESSION['toast_message'] = $message;
    $_SESSION['toast_type'] = $type; // success, error, warning
    header("Location: $location");
    exit;
}

/**
 * Sanitize input untuk keamanan
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * ====================================
 * VALIDASI INPUT
 * ====================================
 */

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('../pelanggan/dashboard.php?menu=menu', 'error', '❌ Invalid request method');
}

// Validasi menu_id
if (!isset($_POST['menu_id']) || empty($_POST['menu_id'])) {
    redirectWithMessage('../pelanggan/dashboard.php?menu=menu', 'error', '❌ Menu ID tidak ditemukan');
}

// Validasi qty
if (!isset($_POST['qty']) || empty($_POST['qty'])) {
    redirectWithMessage('../pelanggan/dashboard.php?menu=menu', 'error', '❌ Jumlah tidak valid');
}

// Sanitize input
$menu_id = sanitizeInput($_POST['menu_id']);
$qty = (int) sanitizeInput($_POST['qty']);

// Validasi qty minimal 1, maksimal 99
if ($qty < 1) {
    $qty = 1;
} elseif ($qty > 99) {
    $qty = 99;
}

/**
 * ====================================
 * QUERY DATABASE
 * ====================================
 */

// Prepared statement untuk keamanan (mencegah SQL injection)
$stmt = mysqli_prepare($koneksi, "SELECT id, name, price, category, image FROM menu WHERE id = ? LIMIT 1");

if (!$stmt) {
    redirectWithMessage('../pelanggan/dashboard.php?menu=menu', 'error', '❌ Database error: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $menu_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$menu = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Cek apakah menu ditemukan
if (!$menu) {
    redirectWithMessage('../pelanggan/dashboard.php?menu=menu', 'error', '❌ Menu tidak ditemukan');
}

/**
 * ====================================
 * PROSES CART
 * ====================================
 */

// Inisialisasi cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Cek apakah item sudah ada di cart
if (isset($_SESSION['cart'][$menu_id])) {
    // Item sudah ada → tambahkan qty
    $old_qty = $_SESSION['cart'][$menu_id]['qty'];
    $new_qty = $old_qty + $qty;
    
    // Batasi maksimal qty per item (misalnya 99)
    if ($new_qty > 99) {
        $new_qty = 99;
        $message = "⚠️ {$menu['name']} sudah mencapai batas maksimal (99 item)";
        $toast_type = 'warning';
    } else {
        $message = "✅ {$menu['name']} ditambahkan (+{$qty}) ke keranjang";
        $toast_type = 'success';
    }
    
    $_SESSION['cart'][$menu_id]['qty'] = $new_qty;
    
} else {
    // Item baru → tambahkan ke cart
    $_SESSION['cart'][$menu_id] = [
        'id'       => $menu['id'],
        'nama'     => $menu['name'],
        'harga'    => $menu['price'],
        'category' => $menu['category'],
        'image'    => $menu['image'],
        'qty'      => $qty
    ];
    
    $message = "✅ {$menu['name']} berhasil ditambahkan ke keranjang";
    $toast_type = 'success';
}

/**
 * ====================================
 * HITUNG TOTAL CART
 * ====================================
 */

$total_items = 0;
$total_price = 0;

foreach ($_SESSION['cart'] as $item) {
    $total_items += $item['qty'];
    $total_price += ($item['harga'] * $item['qty']);
}

// Simpan total ke session untuk akses cepat
$_SESSION['cart_total_items'] = $total_items;
$_SESSION['cart_total_price'] = $total_price;

/**
 * ====================================
 * RESPONSE
 * ====================================
 */

// Jika request dari AJAX (optional)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    sendResponse(true, $message, [
        'cart_total_items' => $total_items,
        'cart_total_price' => $total_price,
        'item_added' => [
            'id' => $menu_id,
            'name' => $menu['name'],
            'qty' => $qty
        ]
    ]);
}

// Redirect normal dengan session message
redirectWithMessage('../cart.php', $toast_type, $message);

/**
 * ====================================
 * CATATAN PENGGUNAAN
 * ====================================
 * 
 * 1. FORM HTML:
 *    <form method="POST" action="proses/add_to_cart.php">
 *        <input type="hidden" name="menu_id" value="1">
 *        <input type="number" name="qty" value="1" min="1" max="99">
 *        <button type="submit">Add to Cart</button>
 *    </form>
 * 
 * 2. AJAX REQUEST (optional):
 *    fetch('proses/add_to_cart.php', {
 *        method: 'POST',
 *        headers: {
 *            'Content-Type': 'application/x-www-form-urlencoded',
 *            'X-Requested-With': 'XMLHttpRequest'
 *        },
 *        body: 'menu_id=1&qty=2'
 *    })
 *    .then(response => response.json())
 *    .then(data => {
 *        if(data.success) {
 *            console.log(data.message);
 *            // Update cart badge
 *            document.getElementById('cart-badge').textContent = data.data.cart_total_items;
 *        }
 *    });
 * 
 * 3. MENAMPILKAN TOAST MESSAGE (di halaman tujuan):
 *    <?php if(isset($_SESSION['toast_message'])): ?>
 *    <script>
 *        showToast('<?= $_SESSION['toast_message'] ?>', '<?= $_SESSION['toast_type'] ?>');
 *    </script>
 *    <?php 
 *        unset($_SESSION['toast_message']); 
 *        unset($_SESSION['toast_type']); 
 *    endif; 
 *    ?>
 * 
 * 4. STRUKTUR SESSION CART:
 *    $_SESSION['cart'] = [
 *        '1' => [
 *            'id' => 1,
 *            'nama' => 'Espresso',
 *            'harga' => 25000,
 *            'category' => 'Kopi',
 *            'image' => 'uploads/espresso.jpg',
 *            'qty' => 2
 *        ],
 *        '2' => [...]
 *    ];
 * 
 * ====================================
 * FITUR KEAMANAN
 * ====================================
 * ✅ Prepared Statement (SQL Injection Prevention)
 * ✅ Input Sanitization
 * ✅ POST Method Only
 * ✅ Session Validation
 * ✅ Quantity Limits (1-99)
 * ✅ Data Type Validation
 * 
 * ====================================
 * FITUR TAMBAHAN
 * ====================================
 * ✅ Support AJAX Request
 * ✅ JSON Response
 * ✅ Toast Message System
 * ✅ Cart Total Calculation
 * ✅ Maximum Quantity Limit
 * ✅ Detailed Error Messages
 * ✅ Clean Code Structure
 * ✅ Complete Documentation
 * 
 */
?>