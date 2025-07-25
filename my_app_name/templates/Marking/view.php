<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marking for <?= h($user->name) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f9f9f9;
            color: #444;
            margin: 0;
            min-height: 100vh;
        }
        .container,
        .assessment-card,
        .intro-card,
        .student-card {
            width: 80vw !important;
            max-width: 900px;
            min-width: 280px;
            box-sizing: border-box;
            margin-left: auto;
            margin-right: auto;
        }
        .container {
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #e0e0e0;
            padding: 40px 16px;
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
            width: 100% !important;
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
        .assessment-desc {
            color: #000;
            margin-bottom: 18px;
        }
        .student-card {
            background: #faf6f6;
            border-radius: 8px;
            margin-bottom: 18px;
            padding: 18px 16px 10px 16px;
            box-shadow: 0 1px 4px #e0e0e0;
            width: 100% !important;
        }
        .student-title {
            font-size: 1.08em;
            font-weight: bold;
            color: #b93b27;
            margin-bottom: 10px;
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
            font-weight: bold; /* Make button text bold */
        }
        .attempt-link:hover {
            background: #b93b27;
        }
        .intro-card {
            background: #fff7f6;
            border-radius: 10px;
            box-shadow: 0 1px 4px #f5b7b1;
            padding: 24px 20px 18px 20px;
            margin-bottom: 36px;
            text-align: center;
            width: 100% !important;
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
            .container,
            .assessment-card,
            .intro-card,
            .student-card {
                width: 98vw !important;
                min-width: 0;
                max-width: 100vw;
                padding-left: 4px;
                padding-right: 4px;
            }
            .assessment-card {
                padding: 16px 8px;
            }
            .student-card {
                padding: 12px 6px 6px 6px;
            }
            .intro-card {
                padding: 14px 6px 10px 6px;
            }
        }
    </style>
</head>
<body>
        <div class="intro-card">
            <div class="intro-title">Marking for <?= h($user->name) ?></div>
            <div class="intro-desc">
                Below are the assessments submitted and attempts for this student. Click an attempt to view marking details.
            </div>
            <?php if (!empty($data)): ?>
                <div style="margin-top: 20px;">
                    <?= $this->Form->postLink(
                        'Mark All Attempts',
                        ['controller' => 'Assessments', 'action' => 'markAll', $user->id],
                        [
                            'class' => 'mark-all-btn',
                            'confirm' => 'Are you sure you want to mark all attempts? This may take a moment.'
                        ]
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php foreach ($data as $block): ?>
            <div class="assessment-card">
                <div class="assessment-title"><?= h($block['assessment']->title) ?></div>
                <div class="assessment-desc"><?= h($block['assessment']->description) ?></div>
                <?php if (empty($block['studentAttempts'])): ?>
                    <div style="color:#b93b27; font-weight:bold; margin-bottom:20px;">No attempt to mark</div>
                <?php else: ?>
                    <?php foreach ($block['studentAttempts'] as $studentId => $attempts): ?>
                        <div class="student-card">
                            <div class="student-title"><?= h($block['studentNames'][$studentId] ?? 'Unknown Student') ?></div>
                            <?php foreach ($attempts as $attempt): ?>
                                <?= $this->Html->link(
                                    'Attempt ' . $attempt->attempt_number,
                                    [
                                        'controller' => 'Assessments',
                                        'action' => 'attemptView',
                                        $studentId,
                                        $block['assessment']->id,
                                        $attempt->attempt_number,
                                        $user->id
                                    ],
                                    ['class' => 'attempt-link']
                                ) ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>