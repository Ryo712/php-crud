<?php
// データベース接続
$db = new PDO('sqlite:database.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// データ取得
$id = $_GET['id'];
$stmt = $db->prepare('SELECT * FROM items WHERE id = :id');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Detail</title>
</head>
<body>
    <h1>Detail Page</h1>
    <h2><?php echo htmlspecialchars($item['title']); ?></h2>
    <p><?php echo htmlspecialchars($item['description']); ?></p>
    <a href=\"index.php\">Back to List</a>
</body>
</html>
