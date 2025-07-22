<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client;

use function PHPUnit\Framework\isNull;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class AssessmentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function student($id = null)
    {
        // Example: Get all assessments for this student/user
        // Adjust as needed for your schema
        $userTable = $this->getTableLocator()->get('Users');
        $user = $userTable->find()->where(['id' => $id])->first();
        $assessments = $this->Assessments->find()->all();
        $this->set(compact('assessments', 'user'));
    }
    public function attempt($userId, $assessmentId)
    {
        // Get the assessment and its questions via the join table
        $assessment = $this->Assessments->get($assessmentId, [
            'contain' => ['AssessmentQuestions' => ['Questions']]
        ]);
        $questions = [];
        foreach ($assessment->assessment_questions as $aq) {
            if ($aq->question) {
                $questions[] = $aq->question;
            }
        }

        if ($this->request->is('post')) {
            $answersTable = $this->getTableLocator()->get('Answers');
            $data = $this->request->getData('answers');
            $maxAttempt = $answersTable->find()
                ->where([
                    'student_id' => $userId,
                    'assessment_id' => $assessmentId
                ])
                ->select(['attempt_number' => 'MAX(attempt_number)'])
                ->first();
            $attemptNumber = $maxAttempt ? $maxAttempt->attempt_number + 1 : 1;
            foreach ($questions as $question) {
                $answerEntity = $answersTable->newEmptyEntity();
                $answerEntity->student_id = $userId;
                $answerEntity->assessment_id = $assessmentId;
                $answerEntity->question_id = $question->id;
                $answerEntity->answer_text = $data[$question->id] ?? '';
                $answerEntity->attempt_number = $attemptNumber;
                $answersTable->save($answerEntity);
            }
            $this->Flash->success(__('Your answers have been submitted.'));
            return $this->redirect(['action' => 'student', $userId]);
        }

        $this->set(compact('assessment', 'questions', 'userId', 'assessmentId'));
    }
public function attemptView($userId, $assessmentId, $attemptNumber, $currentUserId = null)
{

    $assessment = $this->Assessments->get($assessmentId, [
        'contain' => ['AssessmentQuestions' => ['Questions']]
    ]);
    $answersTable = $this->getTableLocator()->get('Answers');
    $answersList = $answersTable->find()
        ->where([
            'student_id' => $userId,
            'assessment_id' => $assessmentId,
            'attempt_number' => $attemptNumber
        ])
    ->toArray();
    // Manually index answers by question_id
    $answers = [];
    foreach ($answersList as $answer) {
        $answers[$answer->question_id] = $answer;
    }

    // Get questions for this assessment
    $questions = [];
    foreach ($assessment->assessment_questions as $aq) {
        if ($aq->question) {
            $questions[] = $aq->question;
        }
    }
    $role="student";
    if (!empty($currentUserId)) {
    
    $userTable = $this->getTableLocator()->get('Users');
    $currentUser = $userTable->find()->where(['id' => $currentUserId])->first();
    
    $role = $currentUser->role ?? "student";
    }
    $this->set(compact('assessment', 'questions', 'answers', 'attemptNumber','role','userId'));
}
public function mark($assessmentId, $attemptNumber,$userId)
{
    require_once __DIR__ . '/../../vendor/autoload.php';

    $answersTable = $this->getTableLocator()->get('Answers');
    $questionsTable = $this->getTableLocator()->get('Questions');

    // Get all answers for this assessment and attempt
    $answers = $answersTable->find()
        ->where([
            'student_id' => $userId,
            'assessment_id' => $assessmentId,
            'attempt_number' => $attemptNumber
        ])
        ->all();
    $guzzle = new \GuzzleHttp\Client([
    'base_uri' => 'https://ai-marker.australiacentral.cloudapp.azure.com/api/',
    'timeout' => 5.0,
]);

// $client = new \MarkLLM\Client\MarksClient($guzzle, null, 'aik_Nk8LAWcyDGsw8aVNbI254m7xzCUD2HRzr_QzQMcc0Q4');
// $status = $client->checkHealth();


    $client = new \MarkLLM\Client\MarksClient(null,"https://ai-marker.australiacentral.cloudapp.azure.com/api/",'aik_Nk8LAWcyDGsw8aVNbI254m7xzCUD2HRzr_QzQMcc0Q4',['verify' => false]);
    #$client = new \MarkLLM\Client\MarksClient(null,"https://ai-marker.australiacentral.cloudapp.azure.com/api",'aik_Nk8LAWcyDGsw8aVNbI254m7xzCUD2HRzr_QzQMcc0Q4',['verify' => false]);
    $status = $client->checkHealth();
    if ($status!='healthy') {
        $this->Flash->error('AI Marker service is currently unavailable.');
        return $this->redirect($this->referer());
    }
    foreach ($answers as $answer) {
        $questionEntity = $questionsTable->get($answer->question_id);

        $question = new \MarkLLM\Models\QuestionModel(
            $questionEntity->question_text,
            $questionEntity->model_answer,
            $answer->answer_text
        );
        
        try {
            $marks = $client->getMarks($question);
            if ($marks) {
                $answer->ai_score = $marks->getScore();
                $answer->ai_feedback = $marks->getFeedback();
                // Save the updated answer
                $answersTable->save($answer);
            }
        } catch (\MarkLLM\Exception\ApiException $e) {
            $this->Flash->error('API Error: ' . $e->getResponseBody());
            echo $e;
            echo $e->getResponseBody();
        } catch (\Exception $e) {
            $this->Flash->error('Unexpected error: ' . $e->getMessage());
        }
    }

    $this->Flash->success('Marking complete!');
    return $this->redirect($this->referer());
}
}