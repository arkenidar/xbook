<?php
ini_set('display_errors', 1);

// init PDO
require 'db-user.php';
$dbh = new PDO('mysql:host=localhost;dbname='.$dbname, $dbuser, $dbpass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// - username handling
session_start();
$username = (string)@$_SESSION["user-name"];

// - id handling for currently-showed entry
$id = (int)@$_REQUEST['id'];

$stmt = $dbh->prepare('SELECT * FROM diary_entries WHERE id=:id');
$stmt->bindParam(':id', $id);
$stmt->execute();

$row = $stmt->fetch();
if($row)
$current_entry_text = $row['message_text'];
else
$current_entry_text = '(no entry with this id was found)';

// - submitted_text handling
$submitted_text = (string)@$_REQUEST['submitted_text'];
if($submitted_text!='' && $username){
$stmt = $dbh->prepare('INSERT INTO diary_entries (message_text) VALUES (:submitted_text)');
$stmt->bindParam(':submitted_text', $submitted_text);
$stmt->execute();
}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>xbook</title>
</head>
<body>
<a href="?id=<?= $id-1 ?>">prev</a> id=<?= $id ?> <a href="?id=<?= $id+1 ?>">next</a>
<p><?= htmlspecialchars($current_entry_text) ?></p>
<?php if($username){ ?>
<form action="" method="post">
<input type="textarea" name="submitted_text">
<input type="submit" value="add">
</form>
<?php } ?>
</body>
</html>
