<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $questions = $_POST['questions'];

    $stmt = $pdo->prepare("INSERT INTO polls (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $description]);
    $poll_id = $pdo->lastInsertId();

    foreach ($questions as $question) {
        $stmt = $pdo->prepare("INSERT INTO questions (poll_id, question_text) VALUES (?, ?)");
        $stmt->execute([$poll_id, $question['text']]);
        $question_id = $pdo->lastInsertId();

        foreach ($question['answers'] as $answer) {
            $stmt = $pdo->prepare("INSERT INTO answers (question_id, answer_text) VALUES (?, ?)");
            $stmt->execute([$question_id, $answer]);
        }
    }
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Створити опитування</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h1>Створення нового опитування</h1>
        <form action="create_poll.php" method="POST">
            <input type="text" name="title" placeholder="Назва опитування" required>
            <textarea name="description" placeholder="Опис опитування"></textarea>
            
            <div id="questions">
                <!-- Поля для запитань будуть динамічно додані через JavaScript -->
            </div>
            <button type="button" onclick="addQuestion()">Додати питання</button>
            <button type="submit">Зберегти опитування</button>
        </form>
    </div>

    <script>
        function addQuestion() {
            const questionsDiv = document.getElementById('questions');
            const questionIndex = questionsDiv.children.length;

            const questionDiv = document.createElement('div');
            questionDiv.className = "question"; // додаємо клас для стилів
            questionDiv.innerHTML = `
                <input type="text" name="questions[${questionIndex}][text]" placeholder="Питання" required>
                <input type="text" name="questions[${questionIndex}][answers][]" placeholder="Варіант відповіді" required>
                <button type="button" onclick="addAnswer(${questionIndex})">Додати варіант відповіді</button>
            `;
            questionsDiv.appendChild(questionDiv);
        }

        function addAnswer(questionIndex) {
            const questionDiv = document.querySelectorAll('#questions > div')[questionIndex];
            const answerInput = document.createElement('input');
            answerInput.setAttribute('type', 'text');
            answerInput.setAttribute('name', `questions[${questionIndex}][answers][]`);
            answerInput.setAttribute('placeholder', 'Варіант відповіді');
            questionDiv.appendChild(answerInput);
        }
    </script>
</body>
</html>
