<?php
session_start();
include "db_connect3.php";

if (!isset($_SESSION["employee_id"])) {
    header("Location: login2.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_menu_item'])) {
    $new_item_name = $_POST['new_item_name'];
    $new_item_price = floatval($_POST['new_item_price']);

    if (!empty($new_item_name) && $new_item_price > 0) {
        $stmt = $conn->prepare("INSERT INTO menu (name, price) VALUES (?, ?)");
        $stmt->bind_param("sd", $new_item_name, $new_item_price);
        $stmt->execute();
        $stmt->close();
        
        
        header("Location: menu.php");
        exit();
    }
}


$query = "SELECT id, name, price FROM menu";
$result = $conn->query($query);
$menu_items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}


$total_amounts = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order']) && isset($_POST['order'])) {
    foreach ($_POST['order'] as $id => $details) {
        $quantity = intval($details['quantity']);
        if ($quantity > 0) {
            $price_query = $conn->prepare("SELECT name, price FROM menu WHERE id = ?");
            $price_query->bind_param("i", $id);
            $price_query->execute();
            $price_query->bind_result($item_name, $price);
            $price_query->fetch();
            $price_query->close();

            $subtotal = $quantity * $price;
            $total_amounts[$id] = $subtotal; 
        }
    }
}


if (isset($_POST['delete_menu_item'])) {
    $delete_id = $_POST['delete_id'];
    
    $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: menu.php");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Brewlane Café</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; display: flex; flex-direction: column; align-items: center; }
        .navbar { width: 100%; padding: 15px; background-color: orange; position: fixed; top: 0; left: 0; display: flex; justify-content: space-between; align-items: center; }
        .container { width: 90%; max-width: 800px; background: white; padding: 20px; margin-top: 80px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4a5cd4; color: white; }
        .action-buttons { display: flex; justify-content: center; gap: 5px; }
        button { padding: 8px; background-color: #4a5cd4; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #3b4bbd; }
        .delete-btn { background-color: red; }
        .delete-btn:hover { background-color: darkred; }
        .total-box { font-size: 16px; font-weight: bold; color: #d9534f; margin-top: 10px; }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <h1>Brewlane Cafe</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php">Menu</a></li>
                    <li><a href="index.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Add New Item</h2>
        <form method="post">
            <input type="text" name="new_item_name" placeholder="Item Name" required>
            <input type="number" step="0.01" name="new_item_price" placeholder="Price" required>
            <button type="submit" name="add_menu_item">Add Item</button>
        </form>
    </div>
    
    <div class="container">
        <h2>Menu Items</h2>
        <form method="post">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Hot/Cold</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo number_format($item['price'], 2); ?> PHP</td>
                        <td>
                            <select name="order[<?php echo $item['id']; ?>][temperature]">
                                <option value="Hot">Hot</option>
                                <option value="Cold">Cold</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="order[<?php echo $item['id']; ?>][quantity]" value="1" min="1">
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button type="submit" name="place_order">Place Order</button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="delete_menu_item" class="delete-btn">Delete</button>
                                </form>
                            </div>
                            <?php if (isset($total_amounts[$item['id']])): ?>
                                <div class="total-box">
                                    Total: <?php echo number_format($total_amounts[$item['id']], 2); ?> PHP
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    </div>
</body>
</html>


