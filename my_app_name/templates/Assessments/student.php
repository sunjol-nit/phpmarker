<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var iterable<\App\Model\Entity\Assessment> $assessments
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= h($user->name) ?> - All Assessments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f9f9f9;
            color: #444;
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #e0e0e0;
            padding: 40px 16px;
            box-sizing: border-box;
        }
        h1, h2, h4 { color: #c0392b; }
        h1 { margin-top: 0; }
        hr { border: none; border-top: 1px solid #eee; margin: 32px 0; }
        .assessment-card {
            background: #fff;
            border-radius: 10px;
            margin-bottom: 32px;
            box-shadow: 0 2px 8px #e0e0e0, 0 4px 12px #f5b7b1;
            padding: 28px 20px 20px 20px;
            transition: box-shadow 0.2s;
            position: relative;
        }
        .assessment-card:hover {
            box-shadow: 0 4px 16px #f5b7b1, 0 8px 24px #e74c3c22;
        }
        .assessment-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 8px;
            color: #c0392b;
        }
        .assessment-actions {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .attempt-link {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 6px;
            padding: 6px 14px;
            background: #e74c3c;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 1em;
            border: none;
            font-weight: 500;
        }
        .attempt-link:hover {
            background: #b93b27;
            color: #fff;
        }
        .new-attempt-link {
            background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 22px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
            margin-bottom: 6px;
            margin-right: 10px;
            display: inline-block;
        }
        .new-attempt-link:hover {
            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
            color: #fff;
        }
        .intro-card {
            background: #fff7f6;
            border-radius: 10px;
            box-shadow: 0 1px 4px #f5b7b1;
            padding: 24px 20px 18px 20px;
            margin-bottom: 36px;
            text-align: center;
        }
        .intro-title {
            font-size: 1.4em;
            font-weight: bold;
            color: #c0392b;
            margin-bottom: 10px;
        }
        .intro-desc {
            color: #000;
            font-size: 1.08em;
        }
        @media (max-width: 600px) {
            .container {
                padding: 16px 4px;
            }
            .assessment-card {
                padding: 16px 8px;
            }
            .intro-card {
                padding: 14px 6px 10px 6px;
            }
        }
    </style>
</head>
<body>
        <div class="intro-card">
            <div class="intro-title"><?= h($user->name) ?> - All Assessments</div>
            <div class="intro-desc">
                Below are all assessments available to you. You can start a new attempt or view your previous attempts for each assessment.
            </div>
        </div>
        <?php foreach ($assessments as $assessment): ?>
            <div class="assessment-card">
                <div class="assessment-title"><?= h($assessment->title) ?></div>
                <div class="assessment-actions">
                    <?= $this->Html->link(
                        'New Attempt',
                        ['controller' => 'Assessments', 'action' => 'attempt', $user->id, $assessment->id],
                        ['class' => 'new-attempt-link']
                    ) ?>
                    <?php
                    // Fetch all attempt numbers for this user/assessment
                    $answersTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Answers');
                    $attempts = $answersTable->find()
                        ->select(['attempt_number'])
                        ->where(['student_id' => $user->id, 'assessment_id' => $assessment->id])
                        ->distinct(['attempt_number'])
                        ->order(['attempt_number' => 'ASC'])
                        ->all();
                    foreach ($attempts as $attempt) {
                        echo $this->Html->link(
                            'Attempt ' . $attempt->attempt_number,
                            ['controller' => 'Assessments', 'action' => 'attemptView', $user->id, $assessment->id, $attempt->attempt_number],
                            ['class' => 'attempt-link']
                        );
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
</body>
</html>