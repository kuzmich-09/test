<?php
require 'db.php';

$query = $pdo->query("SELECT * FROM polls");
$polls = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Опитування</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h1>Доступні опитування</h1>
        <ul>
            <?php foreach ($polls as $poll): ?>
                <li><a href="poll.php?id=<?= $poll['id'] ?>"><?= htmlspecialchars($poll['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div style="text-align: center; margin-top: 20px;">
            <a href="create_poll.php"><button>Створити нове опитування</button></a>
        </div>
    </div>
</body>
</html>
