<?php
// デバッグ用エラー表示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続
$db = new PDO('sqlite:database.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// テーブルが存在しない場合に作成
$db->exec('CREATE TABLE IF NOT EXISTS items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL
);');

// データ削除処理
if (isset($_GET['delete'])) {
    $stmt = $db->prepare('DELETE FROM items WHERE id = :id');
    $stmt->execute([':id' => $_GET['delete']]);
    header('Location: index.php');
    exit;
}

// データの取得
$items = $db->query('SELECT * FROM items ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

// データの追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $db->prepare('INSERT INTO items (title, description) VALUES (:title, :description)');
    $stmt->execute([':title' => $_POST['title'], ':description' => $_POST['description']]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input, textarea {
            display: block;
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: white;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-text {
            flex: 1;
        }
        .item-actions a {
            margin-left: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .item-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>My Application</h1>

    <!-- データ追加フォーム -->
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit" name="add">Add</button>
    </form>

    <h2>Items List</h2>
    <!-- データ一覧 -->
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <span class="item-text"><?php echo htmlspecialchars($item['title']); ?> - <?php echo htmlspecialchars($item['description']); ?></span>
                <span class="item-actions">
                    <a href="edit.php?id=<?php echo htmlspecialchars($item['id']); ?>">Edit</a>
                    <a href="?delete=<?php echo htmlspecialchars($item['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
