<?php
// 汎用関数ファイル読み込み 
require_once MODEL_PATH . 'functions.php';
// DBに関する関数ファイル読み込み
require_once MODEL_PATH . 'db.php';

/**
 * カートデータ取得(二次元連想配列)
 * @param  obj   $db      DBハンドル
 * @param  str   $user_id ユーザID
 * @return array カートデータ(二次元連想配列)
 * @return bool  false
 */
function get_user_carts($db, $user_id){
  // SQL文
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  // SQL文を実行してレコードを取得し返す(二次元連想配列)、例外発生時falseを返す
  return fetch_all_query($db, $sql);
}

/**
 * カートデータを取得(連想配列)
 * @param  obj   $db      DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @return array カートデータ(連想配列)
 * @return bool  false
 */
function get_user_cart($db, $user_id, $item_id){
  // SQL文
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";

  // SQL文を実行してレコードを取得し返す(連想配列)、例外発生時falseを返す
  return fetch_query($db, $sql);

}

/**
 * カートデータ登録、または購入予定数量更新
 * @param  obj   $db      DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @return bool
 */
function add_cart($db, $user_id, $item_id ) {
  // カートデータ取得(連想配列)
  $cart = get_user_cart($db, $user_id, $item_id);
  // 取得出来ないとき
  if($cart === false){
    // カートデータ登録の結果を返す
    return insert_cart($db, $user_id, $item_id);
  }
  // 購入予定数量更新の結果を返す
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

/**
 * カートデータ登録
 * @param  obj   $db         DBハンドル
 * @param  str   $user_id    ユーザID
 * @param  str   $item_id    商品ID
 * @param  str   $amount = 1
 * @return bool
 */
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // SQL文
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * 購入予定数量更新
 * @param  obj   $db      DBハンドル
 * @param  str   $cart_id カートID
 * @param  str   $amount  購入予定量
 * @return bool
 */
function update_cart_amount($db, $cart_id, $amount){
  // SQL文
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * カートデータ削除
 * @param  obj   $db      DBハンドル
 * @param  str   $cart_id カートID
 * @return bool
 */
function delete_cart($db, $cart_id){
  // SQL文
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * カート商品チェックをし、カートデータを削除
 * @param  obj   $db    DBハンドル
 * @param  array $carts
 * @return bool  false
 */
function purchase_carts($db, $carts){
  // カート商品チェックが成功でないとき
  if(validate_cart_purchase($carts) === false){
    // falseを返す
    return false;
  }
  // $carts繰り返し
  foreach($carts as $cart){
    // 在庫数の更新が成功でないとき
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      // セッション変数にエラーメッセージを追加
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  // カートデータ削除
  delete_user_carts($db, $carts[0]['user_id']);
}

/**
 * カートデータ削除
 * @param  obj   $db      DBハンドル
 * @param  str   $user_id ユーザID
 */
function delete_user_carts($db, $user_id){
  // SQL文
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  // SQL文を実行
  execute_query($db, $sql);
}


/**
 * カートの合計金額を取得
 * @param  array $carts
 * @return int   $total_price 合計金額
 */
function sum_carts($carts){
  // $total_priceに0を代入
  $total_price = 0;
  // $carts繰り返し
  foreach($carts as $cart){
    // $total_priceに $cart['price']*$cart['amount'] を足す
    $total_price += $cart['price'] * $cart['amount'];
  }
  // $total_priceを返す
  return $total_price;
}

/**
 * カート商品チェック
 * @param  array $carts
 * @return bool
 */
function validate_cart_purchase($carts){
  // $cartsの数が0のとき
  if(count($carts) === 0){
    // セッション変数にエラーメッセージを追加
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // $carts繰り返し
  foreach($carts as $cart){
    // $cart['status']が1でないとき
    if(is_open($cart) === false){
      // セッション変数にエラーメッセージを追加
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // $cart['stock'] - $cart['amount'] が0より小さいとき
    if($cart['stock'] - $cart['amount'] < 0){
      // セッション変数にエラーメッセージを代追加
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // セッション変数にエラーメッセージがあるとき
  if(has_error() === true){
    // falseを返す
    return false;
  }
  // trueを返す
  return true;
}

