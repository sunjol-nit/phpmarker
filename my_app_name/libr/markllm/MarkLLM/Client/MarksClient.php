<?php
namespace MarkLLM\Client;

use GuzzleHttp\Client;
use MarkLLM\Models\QuestionModel;
use MarkLLM\Models\Marks;
use MarkLLM\Exception\ApiException;

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
            $response = $this->client->get('v1/health/', $this->addHeaders());
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
    public function getMarks(QuestionModel $question): Marks
    {
        if (!$this->client) {
            throw new \RuntimeException('HTTP client is not initialized.');
        }

        if (!$question instanceof QuestionModel) {
            throw new \InvalidArgumentException('Parameter $question must be an instance of QuestionModel.');
        }

        $data = $question->jsonSerialize();

        if (empty($data['question']) || empty($data['model_answer'])) {
            throw new \InvalidArgumentException('Question and model answer must not be empty.');
        }

        if (empty($data['student_answer'])) {
            return new Marks([
                'score' => 0,
                'feedback' => 'No answer provided',
            ]);
        }

        try {
            $response = $this->client->post(
                'v1/marking/',
                $this->addHeaders(['json' => $data])
            );

            $body = $response->getBody()->getContents();
            $responseData = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException('Invalid JSON response: ' . json_last_error_msg());
            }

            if (!is_array($responseData) || !isset($responseData['score'])) {
                throw new ApiException('Invalid response format: missing expected fields.');
            }

            return new Marks($responseData);

        } catch (\Throwable $e) {
            throw $this->wrapRequestException($e);
        }
    }
}
