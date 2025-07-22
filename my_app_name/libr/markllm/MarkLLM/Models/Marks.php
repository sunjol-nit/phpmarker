<?php
namespace MarkLLM\Models;
use \ArrayAccess;
use ReturnTypeWillChange;

class Marks implements ArrayAccess
{
    const DISCRIMINATOR = null;

   

    
    protected $container = [];

    /**
     * Constructor
     *
     * @param array $data Associated array of property values
     */
    public function __construct(array $data = [])
    {
        $this->container['score'] = $data['score'] ?? null;
        $this->container['feedback'] = $data['feedback'] ?? null;
        $this->container['confidence'] = $data['confidence'] ?? null;
        $this->container['processing_time_ms'] = $data['processing_time_ms'] ?? null;
        $this->container['metadata'] = $data['metadata'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['timestamp'] = $data['timestamp'] ?? null;
    }
    
    /**
     * Gets feedback.
     *
     * @return string|null
     */
    public function getFeedback()
    {
        return $this->container['feedback'] ?? null;
    }

    /**
     * Sets feedback.
     *
     * @param string $feedback
     * @return self
     */
    public function setFeedback($feedback)
    {
        $this->container['feedback'] = $feedback;
        return $this;
    }

    /**
     * Gets score.
     *
     * @return float|null
     */
    public function getScore()
    {
        return $this->container['score'] ?? null;
    }

    /**
     * Sets score.
     *
     * @param float $score
     * @return self
     */
    public function setScore($score)
    {
        $this->container['score'] = $score;
        return $this;
    }

    /**
     * Gets success.
     *
     * @return bool|null
     */

    /**
     * Gets confidence.
     *
     * @return float|null
     */
    public function getConfidence()
    {
        return $this->container['confidence'] ?? null;
    }

    /**
     * Sets confidence.
     *
     * @param float $confidence
     * @return self
     */
    public function setConfidence($confidence)
    {
        $this->container['confidence'] = $confidence;
        return $this;
    }

    /**
     * Gets processing_time_ms.
     *
     * @return int|null
     */
    public function getProcessingTimeMs()
    {
        return $this->container['processing_time_ms'] ?? null;
    }

    /**
     * Sets processing_time_ms.
     *
     * @param int $ms
     * @return self
     */
    public function setProcessingTimeMs($ms)
    {
        $this->container['processing_time_ms'] = $ms;
        return $this;
    }

    /**
     * Gets metadata.
     *
     * @return mixed|null
     */
    public function getMetadata()
    {
        return $this->container['metadata'] ?? null;
    }

    /**
     * Sets metadata.
     *
     * @param mixed $metadata
     * @return self
     */
    public function setMetadata($metadata)
    {
        $this->container['metadata'] = $metadata;
        return $this;
    }

    /**
     * Gets status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->container['status'] ?? null;
    }

    /**
     * Sets status.
     *
     * @param string $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;
        return $this;
    }

    /**
     * Gets timestamp.
     *
     * @return string|null
     */
    public function getTimestamp()
    {
        return $this->container['timestamp'] ?? null;
    }

    /**
     * Sets timestamp.
     *
     * @param string $timestamp
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->container['timestamp'] = $timestamp;
        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
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