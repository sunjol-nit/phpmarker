<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Assessment $assessment
 * @var iterable<\App\Model\Entity\Assessment> $assessments
 * @var iterable<\App\Model\Entity\Question> $questions
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= h($assessment->title) ?> - Attempt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9f9f9; color: #222; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 40px; }
        h1, h2, h4 { color: #c0392b; }
        h1 { margin-top: 0; }
        hr { border: none; border-top: 1px solid #eee; margin: 32px 0; }
        .question-card {
            background: #fff;
            border-radius: 10px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px #e0e0e0;
            padding: 24px 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: box-shadow 0.2s;
            position: relative;
        }
        .question-card:hover {
            box-shadow: 0 4px 24px #f5b7b1;
        }
        .question-content {
            flex: 1;
            min-width: 0;
        }
        .question-title {
            font-size: 1.15em;
            font-weight: bold;
            color: #22223b;
            margin-bottom: 8px;
        }
        .answer-area {
            margin-top: 8px;
            margin-bottom: 10px;
        }
        .submit-btn {
            padding: 12px 36px;
            font-size: 1.1em;
            border-radius: 8px;
            background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
            text-decoration: none;
            margin-top: 32px;
            box-shadow: 0 2px 8px #f5b7b1;
            transition: background 0.2s, box-shadow 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            font-weight: bold;
            min-height: 48px; /* Ensures enough height for vertical centering */
        }
        .submit-btn:hover {
            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
            box-shadow: 0 4px 16px #f5b7b1;
            color: #fff;
            text-decoration: none;
        }
        .form-btn-row {
            width: 100%;
            margin-top: 32px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 16px 4px;
            }
            .question-card {
                padding: 16px 8px;
            }
        }
    </style>
</head>
<body>
        <h1 style="text-align:center;"><?= h($assessment->title) ?> - Attempt</h1>
        <?= $this->Form->create() ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($questions as $question): ?>
                <li class="question-card">
                    <div class="question-content">
                        <div class="question-title"><?= h($question->question_text) ?></div>
                        <div class="answer-area">
                            <?= $this->Form->textarea("answers[{$question->id}]", ['rows' => 4, 'style' => 'width:100%; resize:vertical;']) ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="form-btn-row">
            <?= $this->Form->button('Submit Answers', ['class' => 'submit-btn']) ?>
        </div>
        <?= $this->Form->end() ?>
</body>
</html>