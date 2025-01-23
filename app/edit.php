<?php
// エラーレポートの有効化
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// データベース接続
$db = new PDO('sqlite:database.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// IDの確認
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid ID parameter.');
}
$id = $_GET['id'];

// データ取得
$stmt = $db->prepare('SELECT * FROM items WHERE id = :id');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die('Item not found for the given ID.');
}

// データ更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $stmt = $db->prepare('UPDATE items SET title = :title, description = :description WHERE id = :id');
    $stmt->execute([
        ':title' => $_POST['title'],
        ':description' => $_POST['description'],
        ':id' => $id
    ]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <h1>Edit Page</h1>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <button type="submit" name="edit">Save Changes</button>
    </form>
    <a href="index.php">Back to List</a>
</body>
</html>
