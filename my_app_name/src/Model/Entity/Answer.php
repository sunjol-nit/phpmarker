<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Answer Entity
 *
 * @property int $id
 * @property int $assessment_id
 * @property int $question_id
 * @property int $student_id
 * @property int $attempt_number
 * @property string|null $answer_text
 * @property int|null $ai_score
 * @property string|null $ai_feedback
 *
 * @property \App\Model\Entity\Assessment $assessment
 * @property \App\Model\Entity\Question $question
 * @property \App\Model\Entity\User $student
 */
class Answer extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'assessment_id' => true,
        'question_id' => true,
        'student_id' => true,
        'attempt_number' => true,
        'answer_text' => true,
        'ai_score' => true,
        'ai_feedback' => true,
        'assessment' => true,
        'question' => true,
        'student' => true,
    ];
}
