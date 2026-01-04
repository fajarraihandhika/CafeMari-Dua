<?php
session_start();
include "../Koneksi2.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Submit</title>
</head>
<body>
    <h1>Test Form</h1>
    
    <form method="POST" action="process_payment.php">
        <label>Meja ID:</label>
        <input type="text" name="meja_id" value="1" required>
        <br><br>
        
        <label>Payment Method:</label>
        <input type="radio" name="payment_method" value="cash" checked> Cash
        <br><br>
        
        <label>Total:</label>
        <input type="text" name="total" value="50000" required>
        <br><br>
        
        <button type="submit">Submit Test</button>
    </form>
    
    <hr>
    <h2>Semua Meja:</h2>
    <?php
    $meja_query = mysqli_query($koneksi, "SELECT * FROM meja ORDER BY meja_id");
    if ($meja_query && mysqli_num_rows($meja_query) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nomor</th><th>Kapasitas</th></tr>";
        while ($m = mysqli_fetch_assoc($meja_query)) {
            echo "<tr>";
            echo "<td>{$m['meja_id']}</td>";
            echo "<td>{$m['nomor_meja']}</td>";
            echo "<td>{$m['kapasitas']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>TIDAK ADA DATA MEJA!</p>";
    }
    ?>
</body>
</html>