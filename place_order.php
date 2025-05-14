<?php
include 'config.php';
session_start();

date_default_timezone_set('Asia/Manila');

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

if (isset($_POST['confirm_order'])) {

   $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('Query failed');
   $order_date = date("Y-m-d H:i:s");
   $grand_total = 0;
   $ordered_items = [];

   while ($item = mysqli_fetch_assoc($cart_query)) {
      $product_name = $item['name'];
      $price = $item['price'];
      $quantity = $item['quantity'];
      $total = $price * $quantity;

      $grand_total += $total;

      mysqli_query($conn, "INSERT INTO orders (user_id, product_name, price, quantity, order_date) 
         VALUES ('$user_id', '$product_name', '$price', '$quantity', '$order_date')") or die('Insert failed');

      $ordered_items[] = $item;
   }

   // Clear cart
   mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('Failed to clear cart');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Order Placed</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      .receipt-container {
         max-width: 600px;
         margin: 50px auto;
         padding: 20px;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }
      h2 {
         text-align: center;
         color: #28a745;
      }
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }
      th, td {
         padding: 10px;
         border: 1px solid #ccc;
         text-align: center;
      }
      .total {
         font-weight: bold;
      }
   </style>
</head>
<body>

<div class="receipt-container">
<h2>Thank you for your order! 🥳</h2>
<p>Your order has been placed successfully.</p>
<p><strong>Order Date:</strong> <?php echo date("F j, Y, g:i A", strtotime($order_date)); ?></p>


   <table>
      <tr>
         <th>Product</th>
         <th>Price</th>
         <th>Quantity</th>
         <th>Total</th>
      </tr>
      <?php foreach ($ordered_items as $item): ?>
      <tr>
         <td><?php echo $item['name']; ?></td>
         <td>$<?php echo number_format($item['price'], 2); ?></td>
         <td><?php echo $item['quantity']; ?></td>
         <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="total">
         <td colspan="3">Grand Total</td>
         <td>$<?php echo number_format($grand_total, 2); ?></td>
      </tr>
   </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
window.onload = () => {
   confetti({
     particleCount: 200,
     spread: 70,
     origin: { y: 0.6 }
   });
};
</script>


</body>
</html>
