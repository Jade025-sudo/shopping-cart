<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

// Get user cart
$cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('Query failed');
$grand_total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Confirm Your Order</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      .confirm-container {
         max-width: 700px;
         margin: 50px auto;
         padding: 20px;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
      .confirm-btn {
         display: block;
         margin: 30px auto 0;
         padding: 10px 30px;
         background: #28a745;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         font-size: 16px;
      }
   </style>
</head>
<body>

<div class="confirm-container">
   <h2>Review Your Order</h2>
   <form action="place_order.php" method="post">
      <table>
         <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
         </tr>
         <?php while ($item = mysqli_fetch_assoc($cart_query)): 
            $total = $item['price'] * $item['quantity'];
            $grand_total += $total;
         ?>
         <tr>
            <td><?php echo $item['name']; ?></td>
            <td>$<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo $total; ?></td>
         </tr>
         <?php endwhile; ?>
         <tr class="total">
            <td colspan="3">Grand Total</td>
            <td>$<?php echo $grand_total; ?></td>
         </tr>
      </table>

      <button type="submit" class="confirm-btn" name="confirm_order">Confirm Order</button>
   </form>
</div>

</body>
</html>
