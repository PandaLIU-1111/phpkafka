<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetCommit;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetCommitResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The responses for each topic.
     *
     * @var OffsetCommitResponseTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [3, 4, 5, 6, 7, 8], [8], [], [], null),
                new ProtocolField('topics', OffsetCommitResponseTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 8;
    }

    public function getFlexibleVersions(): array
    {
        return [8];
    }

    public function getThrottleTimeMs(): int
    {
        return $this->throttleTimeMs;
    }

    public function setThrottleTimeMs(int $throttleTimeMs): self
    {
        $this->throttleTimeMs = $throttleTimeMs;

        return $this;
    }

    /**
     * @return OffsetCommitResponseTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetCommitResponseTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
