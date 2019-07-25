<?php

namespace ApiVideo\StatusPage\Traits;

use Buzz\Message\MessageInterface;

trait Marshal
{
    /**
     * @param MessageInterface $message
     * @return mixed
     */
    protected function unmarshal(MessageInterface $message)
    {
        return $this->cast(json_decode($message->getContent(), true));
    }

    /**
     * @param MessageInterface $message
     * @return mixed
     */
    protected function unmarshalAll(MessageInterface $message)
    {
        return $this->castAll(json_decode($message->getContent(), true));
    }

    /**
     * @param array $collection
     * @return mixed
     */
    protected function castAll(array $collection)
    {
        return array_map(array($this, 'cast'), $collection);
    }

    abstract protected function cast(array $data);
}
