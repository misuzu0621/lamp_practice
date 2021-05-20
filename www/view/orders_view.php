<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- head.php 読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <!-- orders.css 読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'orders.css'); ?>">
</head>
<body>
  <!-- header_logined.php 読み込み -->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <!-- messages.php 読み込み -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- $ordersの数が0より多いとき -->
    <?php if (count($orders) > 0) { ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
          <!-- $orders繰り返し -->
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td><?php print($order['order_id']); ?></td>
            <td><?php print($order['created']); ?></td>
            <td><?php print(number_format($order['total_price'])); ?>円</td>
            <td>
              <form method="post" action="order_details.php">
                <input type="submit" value="購入明細表示" class="btn btn-secondary">
                <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
                <input type="hidden" name="token" value="<?php print($token); ?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <!-- $ordersの数が0のとき -->
    <?php } else { ?>
      <p>購入履歴はありません</p>
    <?php } ?>
  </div>
</body>
</html>
