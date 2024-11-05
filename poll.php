<?php
require 'db.php';

$poll_id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$query->execute([$poll_id]);
$poll = $query->fetch();

$query = $pdo->prepare("SELECT * FROM questions WHERE poll_id = ?");
$query->execute([$poll_id]);
$questions = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($poll['title']) ?></title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($poll['title']) ?></h1>
        <form action="results.php" method="POST">
            <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <p><?= htmlspecialchars($question['question_text']) ?></p>
                    <?php
                    // Отримуємо варіанти відповідей для поточного питання
                    $answersQuery = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
                    $answersQuery->execute([$question['id']]);
                    $answers = $answersQuery->fetchAll();
                    ?>
                    <?php foreach ($answers as $answer): ?>
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= htmlspecialchars($answer['answer_text']) ?>" required>
                            <?= htmlspecialchars($answer['answer_text']) ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Завершити опитування</button>
        </form>
    </div>
</body>
</html>
