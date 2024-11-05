<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poll_id = $_POST['poll_id'];
    $answers = $_POST['answers'];

    // Зберігання відповідей
    foreach ($answers as $question_id => $answer) {
        $stmt = $pdo->prepare("INSERT INTO results (question_id, answer) VALUES (?, ?)");
        $stmt->execute([$question_id, $answer]);
    }

    // Перенаправлення на сторінку з результатами
    header("Location: results.php?poll_id=$poll_id");
    exit();
}

// Отримання результатів для відображення
$poll_id = $_GET['poll_id'];
$query = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$query->execute([$poll_id]);
$poll = $query->fetch();

$query = $pdo->prepare("SELECT * FROM questions WHERE poll_id = ?");
$query->execute([$poll_id]);
$questions = $query->fetchAll();

$query = $pdo->prepare("SELECT * FROM results WHERE question_id IN (SELECT id FROM questions WHERE poll_id = ?)");
$query->execute([$poll_id]);
$results = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Результати опитування - <?= htmlspecialchars($poll['title']) ?></title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h1>Результати опитування: <?= htmlspecialchars($poll['title']) ?></h1>
        
        <div class="results">
            <h2>Ваші відповіді:</h2>
            <ul>
                <?php foreach ($questions as $question): ?>
                    <li>
                        <strong><?= htmlspecialchars($question['question_text']) ?></strong>
                        <ul>
                            <?php 
                            // Виведення відповідей для кожного питання
                            foreach ($results as $result):
                                if ($result['question_id'] == $question['id']): ?>
                                    <li><?= htmlspecialchars($result['answer']) ?></li>
                                <?php endif; 
                            endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php"><button>Назад до опитувань</button></a>
        </div>
    </div>
</body>
</html>
