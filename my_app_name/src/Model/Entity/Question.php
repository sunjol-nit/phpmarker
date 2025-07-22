<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Question Entity
 *
 * @property int $id
 * @property string $question_text
 * @property string $model_answer
 * @property string|null $question_type
 *
 * @property \App\Model\Entity\Answer[] $answers
 * @property \App\Model\Entity\AssessmentQuestion[] $assessment_questions
 */
class Question extends Entity
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
        'question_text' => true,
        'model_answer' => true,
        'question_type' => true,
        'answers' => true,
        'assessment_questions' => true,
    ];
}
