<?php
session_start();
include "db_connect3.php";

if (!isset($_SESSION["employee_id"])) {
    header("Location: login2.php");
    exit();
}

$order = isset($_SESSION['order']) ? $_SESSION['order'] : [];
if (empty($order)) {
    header("Location: menu.php");
    exit();
}

$order_summary = [];
$total_price = 0;
foreach ($order as $item_id => $details) {
    $query = "SELECT name, price FROM menu WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $subtotal = $row['price'] * $details['quantity'];
        $total_price += $subtotal;
        $order_summary[] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $details['quantity'],
            'temperature' => $details['temperature'],
            'subtotal' => $subtotal
        ];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    foreach ($order as $item_id => $details) {
        $stmt = $conn->prepare("INSERT INTO orders (menu_id, quantity, temperature) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $item_id, $details['quantity'], $details['temperature']);
        $stmt->execute();
        $stmt->close();
    }
    unset($_SESSION['order']);
    header("Location: order_success.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceed to Payment - Brewlane Café</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 20px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4a5cd4; color: white; }
        button { padding: 10px; background-color: #28a745; color: white; border: none; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Summary</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Temp</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($order_summary as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['temperature']; ?></td>
                    <td><?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3>Total: PHP <?php echo number_format($total_price, 2); ?></h3>
        <form method="post">
            <button type="submit" name="confirm_payment">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
