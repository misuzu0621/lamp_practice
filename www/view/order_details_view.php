<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- head.php 読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <!-- order_details.css 読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order_details.css'); ?>">
</head>
<body>
  <!-- header_logined.php 読み込み -->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <!-- messages.php 読み込み -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- $orderの数が0より多いとき -->
    <?php if (count($order_details) > 0) { ?>
      <h2>
        <p>注文番号：<?php print($order['order_id']); ?></p>
        <p>購入日時：<?php print($order['created']); ?></p>
        <p>合計金額：<?php print(number_format($order['total_price'])); ?>円</p>
      </h2>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <!-- $order_details繰り返し -->
          <?php foreach ($order_details as $order_detail) { ?>
          <tr>
            <td><?php print(h($order_detail['name'])); ?></td>
            <td><?php print(number_format($order_detail['price'])); ?>円</td>
            <td><?php print(number_format($order_detail['amount'])); ?></td>
            <td><?php print(number_format($order_detail['subtotal_price'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <!-- $orderの数が0のとき -->
    <?php } else { ?>
      <p>購入明細を表示できません</p>
    <?php } ?>
  </div>
</body>
</html>
