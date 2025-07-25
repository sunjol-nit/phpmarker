<?php
namespace MarkLLM\Models;
use \ArrayAccess;
use \JsonSerializable;
use ReturnTypeWillChange;

class Answers implements ArrayAccess, JsonSerializable
{
    const DISCRIMINATOR = null;

    protected $container = [];

    /**
     * Constructor
     *
     * @param array $data Associated array of property values
     */
    public function __construct($question_id = null,  $student_answer = null, $student_id = null)
    {
        $this->container['question_id'] = $question_id;
        $this->container['student_answer'] = $student_answer;
        $this->container['student_id'] = $student_id;
    }
    
    /**
     * Gets question.
     *
     * @return string|null
     */
    public function getQuestionId()
    {
        return $this->container['question_id'] ?? null;
    }

    /**
     * Sets question.
     *
     * @param string $question
     * @return self
     */
    public function setQuestionId($question_id  = null)
    {
        $this->container['question_id'] = $question_id;
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
     * @param string|null $student_answer
     * @return self
     */
    public function setStudentAnswer($student_answer)
    {
        $this->container['student_answer'] = $student_answer;
        return $this;
    }
    /**
     * Gets student_id.
     *
     * @return int|null
     */
    public function getStudentId()
    {
        return $this->container['student_id'] ?? null;
    }
    /**
     * Sets student_id.
     *
     * @param int|null $student_id
     * @return self
     */
    public function setStudentId($student_id)
    {
        $this->container['student_id'] = $student_id;
        return $this;
    }
    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    public function jsonSerialize(): mixed
    {   
        return $this->container;
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