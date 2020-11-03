<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission5-2</title>
</head>
<body>


<?php

// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 
//テーブル作成   
    $sql = "CREATE TABLE IF NOT EXISTS ita"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "comment TEXT,"
    . "datetime timestamp"//日付と時間はタイムスタンプ型
	.");";
	$stmt = $pdo->query($sql);

    
//投稿フォーム
    if(!empty($_POST["name"])&& !empty($_POST["comment"])&& !empty($_POST["pass0"])){
        $pass0= $_POST["pass0"];
        if($pass0="send"){
            $name = $_POST["name"];
            $comment = $_POST["comment"]; 
            $datetime = date("Y/m/d H:i:s"); 
            if(!empty($_POST["editnum"])){          
                $id = $_POST["editnum"]; //変更する投稿番号を指定する       	
                $sql = 'UPDATE ita SET name=:name,comment=:comment,datetime=:datetime WHERE id=:id';//日時も更新する
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt-> bindParam(':datetime', $datetime, PDO::PARAM_STR);                 
                $stmt->execute();                        
            }else{//普通投稿
                $sql = $pdo -> prepare("INSERT INTO ita (name, comment, datetime) VALUES (:name, :comment, :datetime)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':datetime', $datetime, PDO::PARAM_STR);                                  
                $sql -> execute();
            }
        }
    }   
    
    
//「削除フォーム」
    if (!empty($_POST["delete"])&& !empty($_POST["pass1"])) {
        $pass1= $_POST["pass1"];
        if($pass1="delete"){ 	
            $id = $_POST["delete"];
            $sql = 'delete from ita where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();                   
        }
    } 

//「編集フォーム」（投稿フォームへ飛ばす）   
    if (!empty($_POST["edit"])&& !empty($_POST["pass2"])) {
        $pass2= $_POST["pass2"];
        if($pass2="edit"){
            $edit=$_POST["edit"];                                      
        }        
    }

?>  

<form method= "post" action="mission_5-2.php">
【  投稿フォーム  】<br>
名前：       <input type="text" name="name"  
value="<?php 
if(isset($edit)){$id = $edit ; 
$sql = 'SELECT * FROM ita WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  
$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
$stmt->execute();                             
$results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['name'];
    }
}?>" ><br>

コメント：   <input type="text" name="comment"  
value="<?php 
if(isset($edit)){$id = $edit ; 
$sql = 'SELECT * FROM ita WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  
$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
$stmt->execute();                             
$results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['comment'];
    }
}?>" >
<!--編集用の見えないテキストボックス-->
<input type="hidden" name="editnum" value="<?php if(isset($edit)){echo $edit;}?>" ><br>

パスワード： <input type="text" name="pass0"  value="" ><br>
 <input type="submit" value="送信">
 </form>
 
 <form method= "post" action="mission_5-2.php">
<br>【  削除フォーム  】<br>
 投稿番号：  <input type = "text" name = "delete" ><br>
 パスワード： <input type="text" name="pass1"  value="" ><br>
  <input type = "submit" value="削除" ><br>
</form>

 <form method= "post" action="mission_5-2.php">
<br>【  編集フォーム  】<br>
 投稿番号：  <input type = "text" name = "edit" ><br>
 パスワード： <input type="text" name="pass2"  value="" ><br>
  <input type = "submit" value="編集" ><br><br>
</form>


～投稿一覧～<br>

<?php
    //表示
 $sql = 'SELECT * FROM ita';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['datetime'];
	    echo "<hr>";
	}
?>  



</body>
</html>
