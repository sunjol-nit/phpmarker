<?php
namespace MarkLLM\Client;

use GuzzleHttp\Client;
use MarkLLM\Models\QuestionModel;
use MarkLLM\Models\Marks;
use MarkLLM\Models\Answers;
use MarkLLM\Exception\ApiException;
use Cake\Log\Log;

class MarksClient
{
    private $client;
    private $apiKey;

    /**
     * Constructor
     *
     * @param Client|null $client Optionally pass your own Guzzle client
     * @param string|null $baseUrl Base URL if creating client internally
     * @param string|null $key API Key (must always be provided)
     * @param array $options Additional options if creating client internally
     * @throws \InvalidArgumentException
     */
    public function __construct(Client $client = null, $baseUrl = null, $key = null, $options = [])
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('A valid API key must be provided.');
        }

        $this->apiKey = $key;

        if ($client) {
            $this->client = $client;
        } else {
            $baseUrl = $baseUrl ?? 'http://127.0.0.1:5000/api';

            if (empty($baseUrl) || !filter_var($baseUrl, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException('A valid baseUrl must be provided.');
            }

            $defaultOptions = [
                'base_uri' => $baseUrl,
            ];
            $clientOptions = array_merge($defaultOptions, $options);
            $this->client = new Client($clientOptions);
        }
    }

    /**
     * Adds common headers to request options
     *
     * @param array $options
     * @return array
     */
    private function addHeaders(array $options = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if (!empty($this->apiKey)) {
            $headers['X-API-Key'] = $this->apiKey;
        }

        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        $options['headers'] = array_merge($headers, $options['headers']);

        return $options;
    }

    /**
     * Wraps any exception into an ApiException
     *
     * @param \Throwable $e
     * @return ApiException
     */
    private function wrapRequestException(\Throwable $e): ApiException
    {
        if ($e instanceof \GuzzleHttp\Exception\RequestException) {
            return new ApiException(
                "[{$e->getCode()}] {$e->getMessage()}",
                $e->getCode(),
                $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            );
        }
        return new ApiException(
            "[{$e->getCode()}] {$e->getMessage()}",
            $e->getCode()
        );
    }

    /**
     * Checks the health of the API.
     *
     * @return string
     * @throws ApiException
     */
    public function checkHealth(): string
    {
        if (!$this->client) {
            throw new \RuntimeException('HTTP client is not initialized.');
        }

        try {
            $response = $this->client->get('v2/health/', $this->addHeaders());
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException('Invalid JSON response: ' . json_last_error_msg());
            }

            if (!isset($data['status'])) {
                throw new ApiException('Missing "status" field in health response.');
            }

            return (string)$data['status'];

        } catch (\Throwable $e) {
            throw $this->wrapRequestException($e);
        }
    }

    /**
     * Sends a QuestionModel to the API and returns a Marks object.
     *
     * @param QuestionModel $question
     * @return Marks
     * @throws \InvalidArgumentException
     * @throws ApiException
     */
    public function getMarks(QuestionModel $question, Answers $answer): Marks
    {
        if (!$this->client) {
            throw new \RuntimeException('HTTP client is not initialized.');
        }

        if (!$question instanceof QuestionModel) {
            throw new \InvalidArgumentException('Parameter $question must be an instance of QuestionModel.');
        }

        $data = $question->jsonSerialize();
        
        // Log the initial question data
        Log::write('debug', 'Question Data before student answer: ' . json_encode($data, JSON_PRETTY_PRINT));
        
        $data['student_answer'] = $answer->getStudentAnswer();
        
        // Log the complete data being sent
        Log::write('debug', 'Complete marking data: ' . json_encode($data, JSON_PRETTY_PRINT));

        if (empty($data['question_text']) || empty($data['model_answer'])) {
            Log::write('error', 'Empty required fields: ' . json_encode([
                'question_empty' => empty($data['question_text']),
                'model_answer_empty' => empty($data['model_answer'])
            ]));
            throw new \InvalidArgumentException('Question and model answer must not be empty.');
        }

        if (empty($data['student_answer'])) {
            Log::write('debug', 'No student answer provided');
            return new Marks([
                'score' => 0,
                'feedback' => 'No answer provided',
            ]);
        }

        try {
            // Log the API request
            Log::write('debug', 'Sending request to marking API');
            
            $response = $this->client->post(
                'v2/marking/',
                $this->addHeaders(['json' => $data])
            );

            $body = $response->getBody()->getContents();
            
            // Log the API response
            Log::write('debug', 'API Response: ' . $body);
            
            $responseData = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException('Invalid JSON response: ' . json_last_error_msg());
            }

            if (!is_array($responseData) || !isset($responseData['score'])) {
                throw new ApiException('Invalid response format: missing expected fields.');
            }

            return new Marks($responseData);

        } catch (\Throwable $e) {
            Log::write('error', 'API Error: ' . $e->getMessage());
            throw $this->wrapRequestException($e);
        }
    }
    public function getMarksAll(array $questions, array $answers)
    {
        if (!$this->client) {
            throw new \RuntimeException('HTTP client is not initialized.');
        }

        foreach ($questions as $question) {
            if (!$question instanceof QuestionModel) {
                throw new \InvalidArgumentException('Parameter $question must be an instance of QuestionModel.');
            }

        $output = [
    'questions' => $questions,
    'responses' => $answers,
    ];
        $json = str_replace(["\n", "\r"], '', json_encode($output));

        // Log the initial question data
        Log::write('debug', 'Initial Question Data: ' . $json);

        // Log the complete data being sent

        

        try {
            // Log the API request
            
            $response = $this->client->post(
                'v2/batch/',
                $this->addHeaders(['json' => $output])
            );

            $body = $response->getBody()->getContents(); // this is a string
Log::debug('Raw API Response: ' . $body);
            $data = json_decode($body, true);
            $marksList= [];
            foreach ($data['results'] as $item) {
        try {
            $marksList[] = new Marks($item);
        } catch (\Throwable $e) {
            Log::error('Failed to create Marks object: ' . $e->getMessage());
            // Optionally: continue, throw, or add null
        }
    }
    return $marksList;
            
            // Log the API response
            
            // $responseData = json_decode($body, true);
            // if (json_last_error() !== JSON_ERROR_NONE) {
            //     throw new ApiException('Invalid JSON response: ' . json_last_error_msg());
            // }

            // if (!is_array($responseData) || !isset($responseData['score'])) {
            //     throw new ApiException('Invalid response format: missing expected fields.');
            // }

            // return new Marks($responseData);

        } catch (\Throwable $e) {
            Log::write('error', 'API Error:yo yoyoyoyoyyo ' . $e->getMessage());
            throw $this->wrapRequestException($e);
        }
    }
}}
