<?php
/**
 * MarkLLM SDK Documentation Page (CakePHP)
 *
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MarkLLM PHP SDK Documentation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9f9f9; color: #222; margin: 0; }
        .container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 40px; }
        h1, h2, h3 { color: #c0392b; }
        pre, code { background: #f4f4f4; border-radius: 4px; padding: 2px 6px; }
        pre { padding: 12px; overflow-x: auto; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
        hr { border: none; border-top: 1px solid #eee; margin: 32px 0; }
        ul { margin-left: 1.5em; }
        .section { margin-bottom: 32px; }
        .note { background: #fffbe6; border-left: 4px solid #ffe066; padding: 12px 16px; margin: 16px 0; border-radius: 4px; }
        .success { color: #27ae60; }
        .problem { color: #c0392b; }
    </style>
</head>
<body>
<div class="container">
    <h1>MarkLLM PHP SDK Documentation</h1>
    <p class="note">
        The MarkLLM PHP SDK provides a clean, testable interface to the MarkLLM API for automated marking and feedback.
    </p>

    <div class="section">
        <h2>Overview</h2>
        <ul>
            <li>Flexible <code>MarksClient</code> with optional Guzzle injection</li>
            <li><code>QuestionModel</code> for question input</li>
            <li><code>Marks</code> for response output</li>
            <li>API key authentication via headers</li>
        </ul>
    </div>

    <div class="section">
        <h2>Installation</h2>
        <ol>
            <li><strong>Install via Composer</strong><br>
                <pre>composer require guzzlehttp/guzzle</pre>
            </li>
            <li><strong>Include the SDK in your project</strong><br>
                <pre>require_once '/path/to/vendor/autoload.php';</pre>
            </li>
        </ol>
    </div>

    <div class="section">
        <h2>MarksClient Usage</h2>
        <h3>1. Initialize the Client</h3>
        <p>You can inject your own <code>GuzzleHttp\Client</code> or let the SDK create it internally:</p>

        <h4>Internal client example</h4>
        <pre><code>use MarkLLM\Client\MarksClient;

$client = new MarksClient(
    null,                          // No custom client
    'https://your-api-url/api',    // Base API URL
    'your_api_key'                 // API Key
);</code></pre>

        <h4>Custom Guzzle client example</h4>
        <pre><code>use MarkLLM\Client\MarksClient;
use GuzzleHttp\Client as GuzzleClient;

$guzzle = new GuzzleClient([
    'base_uri' => 'https://your-api-url/api',
    'timeout' => 10.0
]);

$client = new MarksClient($guzzle, null, 'your_api_key');</code></pre>

        <h3>2. Mark a Student Answer</h3>
        <pre><code>use MarkLLM\Models\QuestionModel;

$question = new QuestionModel(
    "What is the capital of France?",
    "The capital of France is Paris.",
    "The capital of France is Paris."
);

try {
    $marks = $client->getMarks($question);
    if ($marks) {
        echo "Score: " . $marks->getScore();
        echo "Feedback: " . $marks->getFeedback();
    }
} catch (\MarkLLM\Exception\ApiException $e) {
    echo "API Error: " . $e->getMessage();
}</code></pre>
    </div>

    <div class="section">
        <h2>QuestionModel Reference</h2>
        <p><code>QuestionModel</code> represents the question, model answer, and student answer.</p>

        <h3>Constructor</h3>
        <pre><code>new QuestionModel(
    string $question,
    string $modelAnswer,
    string $studentAnswer
);</code></pre>

        <h3>Example</h3>
        <pre><code>use MarkLLM\Models\QuestionModel;

$question = new QuestionModel(
    "Explain photosynthesis.",
    "Photosynthesis converts light energy into chemical energy in plants.",
    "Plants use sunlight to create food."
);</code></pre>

        <h3>Getters</h3>
        <ul>
            <li><code>getQuestion()</code></li>
            <li><code>getModelAnswer()</code></li>
            <li><code>getStudentAnswer()</code></li>
        </ul>

        <h3>Setters</h3>
        <ul>
            <li><code>setQuestion($question)</code></li>
            <li><code>setModelAnswer($answer)</code></li>
            <li><code>setStudentAnswer($answer)</code></li>
        </ul>

        <h3>JSON Serialization</h3>
        <pre><code>$data = $question->jsonSerialize();
/*
[
    'question' => 'Explain photosynthesis.',
    'model_answer' => 'Photosynthesis converts light energy...',
    'student_answer' => 'Plants use sunlight to create food.'
]
*/</code></pre>

        <h3>ArrayAccess</h3>
        <pre><code>$question = new QuestionModel(...);

echo $question['question'];
$question['student_answer'] = 'Updated answer';</code></pre>
    </div>

    <div class="section">
        <h2>Marks Reference</h2>
        <p><code>Marks</code> represents the response returned by the API after marking.</p>

        <h3>Constructor</h3>
        <pre><code>new Marks(array $data);</code></pre>

        <h3>Example</h3>
        <pre><code>use MarkLLM\Models\Marks;

$marks = new Marks([
    'score' => 0.85,
    'feedback' => 'Good explanation, but missing chlorophyll details.',
    'confidence' => 0.92,
    'processing_time_ms' => 120,
    'metadata' => ['model_version' => 'v1.2'],
    'status' => 'completed',
    'timestamp' => '2024-07-01T10:15:00Z'
]);</code></pre>

        <h3>Getters</h3>
        <ul>
            <li><code>getScore()</code></li>
            <li><code>getFeedback()</code></li>
            <li><code>getConfidence()</code></li>
            <li><code>getProcessingTimeMs()</code></li>
            <li><code>getMetadata()</code></li>
            <li><code>getStatus()</code></li>
            <li><code>getTimestamp()</code></li>
        </ul>

        <h3>Setters</h3>
        <ul>
            <li><code>setScore($score)</code></li>
            <li><code>setFeedback($feedback)</code></li>
            <li><code>setConfidence($confidence)</code></li>
            <li><code>setProcessingTimeMs($ms)</code></li>
            <li><code>setMetadata($metadata)</code></li>
            <li><code>setStatus($status)</code></li>
            <li><code>setTimestamp($timestamp)</code></li>
        </ul>

        <h3>ArrayAccess</h3>
        <pre><code>$marks = new Marks(...);

echo $marks['feedback'];
$marks['score'] = 0.95;
unset($marks['metadata']);</code></pre>
    </div>

    <div class="section">
        <h2>Error Handling</h2>
        <ul>
            <li><strong>ApiException:</strong> Thrown for API/network errors.</li>
            <li><strong>\InvalidArgumentException:</strong> For invalid data.</li>
            <li><strong>\RuntimeException:</strong> If the client is not properly initialized.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Troubleshooting</h2>
        <ul>
            <li><span class="problem">cURL error 28:</span> Check API URL, port, and firewall.</li>
            <li><span class="problem">Invalid API Key:</span> Verify your credentials.</li>
        </ul>
    </div>

    <div class="section">
        <h2>License</h2>
        <p>This SDK is provided under the MIT License.</p>
    </div>

    <div class="section">
        <h2>Support</h2>
        <p>
            For issues, contact your MarkLLM administrator or consult the API documentation.
        </p>
    </div>
</div>
</body>
</html>
