<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssessmentQuestionsFixture
 */
class AssessmentQuestionsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'assessment_id' => 1,
                'question_id' => 1,
            ],
        ];
        parent::init();
    }
}
