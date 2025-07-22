<?php
declare(strict_types=1);

namespace App\Controller;

class MarkingController extends AppController
{
      /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function view($userId)
    {
        $assessmentsTable = $this->getTableLocator()->get('Assessments');
    $answersTable = $this->getTableLocator()->get('Answers');
    $studentsTable = $this->getTableLocator()->get('Users');

    // Get all assessments
    $assessments = $assessmentsTable->find()->all();
    $usersTable = $this->getTableLocator()->get('Users');
    $user = $usersTable->get($userId);

    // Prepare data structure
    $data = [];

    foreach ($assessments as $assessment) {
        // Get all students who have attempted this assessment
        $students = $answersTable->find()
            ->select(['student_id'])
            ->where(['assessment_id' => $assessment->id])
            ->distinct(['student_id'])
            ->all();

        $studentAttempts = [];
        foreach ($students as $student) {
            // Get all attempts for this student and assessment
            $attempts = $answersTable->find()
                ->select(['attempt_number'])
                ->where([
                    'assessment_id' => $assessment->id,
                    'student_id' => $student->student_id
                ])
                ->distinct(['attempt_number'])
                ->order(['attempt_number' => 'ASC'])
                ->all();
            // Only add if there are attempts
            if (count($attempts) > 0) {
                $studentAttempts[$student->student_id] = $attempts;
            }
        }

        // Get student names (only for students with attempts)
        $studentNames = [];
        if (!empty($studentAttempts)) {
            $studentNames = $studentsTable->find('list', [
                'keyField' => 'id',
                'valueField' => 'name'
            ])->where(['id IN' => array_keys($studentAttempts)])->toArray();
        }

        $data[] = [
            'assessment' => $assessment,
            'studentAttempts' => $studentAttempts,
            'studentNames' => $studentNames,
        ];
    }

    $this->set(compact('data', 'user'));
    }
}