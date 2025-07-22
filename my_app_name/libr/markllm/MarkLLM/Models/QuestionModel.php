<?php
namespace MarkLLM\Models;
use \ArrayAccess;
use ReturnTypeWillChange;

class QuestionModel implements ArrayAccess
{
    const DISCRIMINATOR = null;

    protected $container = [];

    /**
     * Constructor
     *
     * @param array $data Associated array of property values
     */
    public function __construct($question = null, $model_answer = null, $student_answer = null)
    {
        $this->container['question'] = $question;
        $this->container['model_answer'] = $model_answer;
        $this->container['student_answer'] = $student_answer;
    }
    
    /**
     * Gets question.
     *
     * @return string|null
     */
    public function getQuestion()
    {
        return $this->container['question'] ?? null;
    }

    /**
     * Sets question.
     *
     * @param string $question
     * @return self
     */
    public function setQuestion($question)
    {
        $this->container['question'] = $question;
        return $this;
    }

    /**
     * Gets model_answer.
     *
     * @return string|null
     */
    public function getModelAnswer()
    {
        return $this->container['model_answer'] ?? null;
    }

    /**
     * Sets model_answer.
     *
     * @param string $model_answer
     * @return self
     */
    public function setModelAnswer($model_answer)
    {
        $this->container['model_answer'] = $model_answer;
        return $this;
    }

    /**
     * Gets student_answer.
     *
     * @return string|null
     */
    public function getStudentAnswer()
    {
        return $this->container['student_answer'] ?? null;
    }
    
    /**
     * Sets student_answer.
     *
     * @param string $student_answer
     * @return self
     */
    public function setStudentAnswer($student_answer)
    {
        $this->container['student_answer'] = $student_answer;
        return $this;
    }
     
     
     
    public function jsonSerialize()
    {
        return [
            'question' => $this->getQuestion(),
            'model_answer' => $this->getModelAnswer(),
            'student_answer' => $this->getStudentAnswer(),
      ];
   }
     
        
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return string
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}