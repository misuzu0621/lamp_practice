<?php
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// DBに関する関数ファイル読み込み
require_once MODEL_PATH . 'db.php';

// DB利用

/**
 * 商品データ取得(連想配列)
 * @param  obj   $db      DBハンドル
 * @param  str   $item_id 商品ID
 * @return array 商品データ(連想配列)
 * @return bool  false
 */
function get_item($db, $item_id){
  // SQL文
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = {$item_id}
  ";

  // SQL文を実行してレコードを取得し返す、例外発生時falseを返す
  return fetch_query($db, $sql);
}

/**
 * 商品データ取得(二次元連想配列)
 * @param  obj   $db DBハンドル
 * @param  bool  $is_open = false
 * @return array 商品データ(二次元連想配列)
 * @return bool  false
 */
function get_items($db, $is_open = false){
  // SQL文
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  // $is_openがtrueのとき
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  // SQLを実行してレコードを取得し返す、例外発生時falseを返す
  return fetch_all_query($db, $sql);
}
/**
 * 商品データ取得(二次元連想配列)
 * @param  obj   $db DBハンドル
 * @return array 商品データ(二次元連想配列)
 * @return bool  false
 */
function get_all_items($db){
  // 商品データを取得して返す(二次元連想配列)、例外発生時falseを返す
  return get_items($db);
}

/**
 * ステータスが公開の商品データ取得(二次元連想配列)
 * @param  obj   $db DBハンドル
 * @return array ステータスが公開の商品データ(二次元連想配列)
 * @return bool  false
 */
function get_open_items($db){
  // ステータスが公開の商品データを取得して返す(二次元連想配列)、例外発生時falseを返す
  return get_items($db, true);
}

/**
 * 入力値チェックを行い、新規商品追加、画像ファイルの移動
 * @param  obj   $db     DBハンドル
 * @param  str   $name   商品名
 * @param  str   $price  値段
 * @param  str   $stock  在庫数
 * @param  str   $status ステータス
 * @param  str   $image  画像ファイル
 * @return bool
 */
function regist_item($db, $name, $price, $stock, $status, $image){
  // 画像ファイル名を取得
  $filename = get_upload_filename($image);
  // 入力値チェックの結果がfalseのとき
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    // falseを返す
    return false;
  }
  // 新規商品追加と画像ファイルの移動が成功したときtrue、そうでないときfalseを返す
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

/**
 * 新規商品追加、画像ファイルの移動
 * @param  obj   $db       DBハンドル
 * @param  str   $name     商品名
 * @param  str   $price    値段
 * @param  str   $stock    在庫数
 * @param  str   $status   ステータス
 * @param  str   $image    画像ファイル
 * @param  str   $filename 画像ファイル名
 * @return bool
 */
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  // トランザクション開始
  $db->beginTransaction();
  // 新規商品追加が成功、
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
  // かつ、画像ファイルの移動が成功したとき  
  && save_image($image, $filename)){
    // コミット
    $db->commit();
    // trueを返す
    return true;
  }
  // ロールバック
  $db->rollback();
  // falseを返す
  return false;
  
}

/**
 * 新規商品追加
 * @param  obj   $db       DBハンドル
 * @param  str   $name     商品名
 * @param  str   $price    値段
 * @param  str   $stock    在庫数
 * @param  str   $filename 画像ファイル名
 * @param  str   $status   ステータス
 * @return bool
 */
function insert_item($db, $name, $price, $stock, $filename, $status){
  // $status_valueにPREMITTED_ITEM_STATUSES[$status]を代入
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  // SQL文
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES('{$name}', {$price}, {$stock}, '{$filename}', {$status_value});
  ";

  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * ステータス更新
 * @param  obj   $db      DBハンドル
 * @param  str   $item_id 商品ID
 * @param  str   $status  ステータス
 * @return bool
 */
function update_item_status($db, $item_id, $status){
  // SQL文
  $sql = "
    UPDATE
      items
    SET
      status = {$status}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * 在庫数を更新
 * @param  obj   $db      DBハンドル
 * @param  str   $item_id 商品ID
 * @param  str   $stock   在庫数
 * @return bool
 */
function update_item_stock($db, $item_id, $stock){
  // SQL文
  $sql = "
    UPDATE
      items
    SET
      stock = {$stock}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}

/**
 * 商品・画像ファイル削除
 * @param  obj   $db      DBハンドル
 * @param  str   $item_id 商品ID
 * @return bool
 */
function destroy_item($db, $item_id){
  // 商品データ取得(連想配列)
  $item = get_item($db, $item_id);
  // $itemがfalseのとき
  if($item === false){
    // falseを返す
    return false;
  }
  // トランザクション開始
  $db->beginTransaction();
  // 商品削除成功、
  if(delete_item($db, $item['item_id'])
  // かつ、画像ファイルの削除が成功したとき
    && delete_image($item['image'])){
    // コミット
    $db->commit();
    // trueを返す
    return true;
  }
  // ロールバック
  $db->rollback();
  // falseを返す
  return false;
}

/**
 * 商品削除
 * @param  obj   $db      DBハンドル
 * @param  str   $item_id 商品ID
 * @return bool
 */
function delete_item($db, $item_id){
  // SQL文
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  // SQL文の実行結果を返す
  return execute_query($db, $sql);
}


// 非DB

/**
 * $item['status']が1かどうか確認
 * @param  array $item
 * @return bool
 */
function is_open($item){
  // $item['status']が1のときtrue、そうでないときfalseを返す
  return $item['status'] === 1;
}

/**
 * 入力値チェック
 * @param  str   $name     商品名
 * @param  str   $price    値段
 * @param  str   $stock    在庫数
 * @param  str   $filename 画像ファイル名
 * @param  str   $status   ステータス
 * @return bool
 */
function validate_item($name, $price, $stock, $filename, $status){
  // $nameの入力値チェックの結果を取得
  $is_valid_item_name = is_valid_item_name($name);
  // $priceの入力値チェックの結果を取得
  $is_valid_item_price = is_valid_item_price($price);
  // $stockの入力値チェックの結果を取得
  $is_valid_item_stock = is_valid_item_stock($stock);
  // $filenameの画像ファイル名のチェックの結果を取得
  $is_valid_item_filename = is_valid_item_filename($filename);
  // $statusのチェックの結果を取得
  $is_valid_item_status = is_valid_item_status($status);

  // 全てがtrueのときtrueを、そうでないときfalseを返す
  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

/**
 * 商品名の入力値チェック
 * @param  str   $name 商品名
 * @return bool  $is_valid
 */
function is_valid_item_name($name){
  // $is_validにtureを代入
  $is_valid = true;
  // $nameの文字数が商品名の最小文字数より少ないまたは商品名の最大文字数より多いとき
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    // セッション変数にエラーメッセージを追加
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * 値段の入力値チェック
 * @param  str   $price 値段
 * @return bool  $is_valid
 */
function is_valid_item_price($price){
  // $is_validにtrueを代入
  $is_valid = true;
  // $priceが正の整数の正規表現にマッチしなかったとき
  if(is_positive_integer($price) === false){
    // セッション変数にエラーメッセージを追加
    set_error('価格は0以上の整数で入力してください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * 在庫数の入力値チェック
 * @param  str   $stock 在庫数
 * @return bool  $is_valid
 */
function is_valid_item_stock($stock){
  // $is_validにtrueを代入
  $is_valid = true;
  // $stockが正の整数の正規表現にマッチしなかったとき
  if(is_positive_integer($stock) === false){
    // セッション変数にエラーメッセージを追加
    set_error('在庫数は0以上の整数で入力してください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * 画像ファイル名のチェック
 * @param  str   $filename 画像ファイル名
 * @return bool  $is_valid
 */
function is_valid_item_filename($filename){
  // is_validにtrueを代入
  $is_valid = true;
  // $filenameが''のとき
  if($filename === ''){
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * ステータスのチェック
 * @param  str  $status ステータス
 * @return bool $is_valid
 */
function is_valid_item_status($status){
  // $is_validにtrueを代入
  $is_valid = true;
  // ステータスが公開または非公開でないとき
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}