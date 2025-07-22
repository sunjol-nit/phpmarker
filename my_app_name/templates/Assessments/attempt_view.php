<?php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= h($assessment->title) ?> - Attempt <?= h($attemptNumber) ?></title>
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
        .answer-text {
            color: #333;
            margin-bottom: 10px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 1em;
            word-break: break-word;
        }
        .feedback {
            margin-top: 8px;
            font-style: italic;
            font-size: 0.97em;
        }
        .feedback-marked {
            color: #e74c3c;
        }
        .feedback-unmarked {
            color: #b1a7a6;
        }
        .score-box {
            min-width: 120px;
            text-align: right;
            font-size: 1.1em;
            margin-left: 18px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
        }
        .score-label {
            font-weight: bold;
            color: #22223b;
            margin-bottom: 4px;
        }
        .score-value {
            color: #e74c3c;
            font-size: 1.2em;
            font-weight: bold;
        }
        .score-unmarked {
            color: #b1a7a6;
            font-size: 1em;
        }
        .mark-btn {
            padding: 12px 36px;
            font-size: 1.1em;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 8px;
            background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
            text-decoration: none;
            margin-top: 32px;
            box-shadow: 0 2px 8px #f5b7b1;
            transition: background 0.2s, box-shadow 0.2s;
            cursor: pointer;
            display: block;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .mark-btn:hover {
            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
            box-shadow: 0 4px 16px #f5b7b1;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
        <h1><?= h($assessment->title) ?> - Attempt <?= h($attemptNumber) ?></h1>
        <hr>
        <ul style="list-style: none; padding: 0;">
        <?php foreach ($questions as $question): ?>
            <li class="question-card">
                <div class="question-content">
                    <div class="question-title"><?= h($question->question_text) ?></div>
                    <div class="answer-text">
                        <?= h($answers[$question->id]->answer_text ?? 'No answer') ?>
                    </div>
                    <div class="feedback">
                        <?php if (!empty($answers[$question->id]) && !empty($answers[$question->id]->ai_feedback)): ?>
                            <span class="feedback-marked">Feedback: <?= h($answers[$question->id]->ai_feedback) ?></span>
                        <?php else: ?>
                            <span class="feedback-unmarked">Feedback: Not marked</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="score-box">
                    <span class="score-label">Score:</span>
                    <?php if (!empty($answers[$question->id]) && $answers[$question->id]->ai_score !== null): ?>
                        <span class="score-value"><?= h($answers[$question->id]->ai_score) ?> marks</span>
                    <?php else: ?>
                        <span class="score-unmarked">Not marked</span>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php if (isset($role) && $role === 'teacher'): ?>
            <div style="text-align:center;">
                <?= $this->Html->link(
                    'Mark',
                    ['controller' => 'Assessments', 'action' => 'mark', $assessment->id, $attemptNumber, $userId],
                    ['class' => 'mark-btn']
                ) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>