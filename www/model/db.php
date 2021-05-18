<?php

/**
 * DB接続
 * @return obj  $dbh DBハンドル
 */
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    // エラーが起きたとき例外を投げる
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // PDO側のエミュレート機能をOFFにする
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // カラム名をキーとする連想配列で取得する
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  // 例外発生時
  } catch (PDOException $e) {
    // プログラムを終了
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  // $dbhを返す
  return $dbh;
}

/**
 * SQL文を実行してレコードを取得(連想配列)
 * @param  obj   $db  DBハンドル
 * @param  str   $sql SQL文
 * @param  array $params = array()
 * @return array $statement->fetch()
 * @return bool  false
 */
function fetch_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    // レコードを取得し、返す
    return $statement->fetch();
    // 例外発生時
  }catch(PDOException $e){
    // セッション変数にエラーメッセージを追加
    set_error('データ取得に失敗しました。');
  }
  // falseを返す
  return false;
}
/**
 * SQL文を実行してレコードを取得(二次元連想配列)
 * @param  obj   $db  DBハンドル
 * @param  str   $sql SQL文
 * @param  array $params = array()
 * @return array $statement->fetchAll()
 * @return bool  false
 */
function fetch_all_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    // レコードを取得し、返す
    return $statement->fetchAll();
    // 例外発生時
  }catch(PDOException $e){
    // セッション変数にエラーメッセージを追加
    set_error('データ取得に失敗しました。');
  }
  // falseを返す
  return false;
}

/**
 * SQL文を実行
 * @param  obj   $db  DBハンドル
 * @param  str   $sql SQL文
 * @param  array $params = array()
 * @return bool
 */
function execute_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQL文の実行が成功のときtrue、そうでないときfalseを返す
    return $statement->execute($params);
    // 例外発生時
  }catch(PDOException $e){
    // セッション変数にエラーメッセージを追加
    set_error('更新に失敗しました。');
  }
  // falseを返す
  return false;
}
