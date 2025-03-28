<?php
/**
 * This file is part of the lenex-php package.
 *
 * The Lenex file format is created by Swimrankings.net
 *
 * (c) Leon Verschuren <lverschuren@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace leonverschuren\Lenex\Model;

class Result
{
    /** @var string|null */
    protected $comment;

    /** @var int|null */
    protected $eventId;

    /** @var int|null */
    protected $heatId;

    /** @var int|null */
    protected $lane;

    /** @var int|null */
    protected $points;

    /** @var string|null */
    protected $reactionTime;

    /** @var RelayPosition[] */
    protected $relayPositions = [];

    /** @var int|null */
    protected $resultId;

    /** @var string|null */
    protected $status;

    /** @var Split[] */
    protected $splits = [];

    /** @var string|null */
    protected $swimTime;

    /** @var string|null */
    protected $entryTime;

    /** @var string|null */
    protected $entryCourse;
    /** @var string|null */
    protected $late;

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     * @return $this
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeatId()
    {
        return $this->heatId;
    }

    /**
     * @param int $heatId
     * @return $this
     */
    public function setHeatId($heatId)
    {
        $this->heatId = $heatId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLane()
    {
        return $this->lane;
    }

    /**
     * @param int $lane
     * @return $this
     */
    public function setLane($lane)
    {
        $this->lane = $lane;

        return $this;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     * @return $this
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return string
     */
    public function getReactionTime()
    {
        return $this->reactionTime;
    }

    /**
     * @param string $reactionTime
     * @return $this
     */
    public function setReactionTime($reactionTime)
    {
        $this->reactionTime = $reactionTime;

        return $this;
    }

    /**
     * @return RelayPosition[]
     */
    public function getRelayPositions()
    {
        return $this->relayPositions;
    }

    /**
     * @param RelayPosition[] $relayPositions
     * @return $this
     */
    public function setRelayPositions($relayPositions)
    {
        $this->relayPositions = $relayPositions;

        return $this;
    }

    /**
     * @return int
     */
    public function getResultId()
    {
        return $this->resultId;
    }

    /**
     * @param int $resultId
     * @return $this
     */
    public function setResultId($resultId)
    {
        $this->resultId = $resultId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Split[]
     */
    public function getSplits()
    {
        return $this->splits;
    }

    /**
     * @param Split[] $splits
     * @return $this
     */
    public function setSplits($splits)
    {
        $this->splits = $splits;

        return $this;
    }

    /**
     * @return string
     */
    public function getSwimTime()
    {
        return $this->swimTime;
    }

    /**
     * @param string $swimTime
     * @return $this
     */
    public function setSwimTime($swimTime)
    {
        $this->swimTime = $swimTime;

        return $this;
    }

    public function getEntryTime(): ?string
    {
        return $this->entryTime;
    }

    public function setEntryTime(string $entryTime): void
    {
        $this->entryTime = $entryTime;
    }

    public function getEntryCourse(): ?string
    {
        return $this->entryCourse;
    }

    public function setEntryCourse(string $entryCourse): void
    {
        $this->entryCourse = $entryCourse;
    }

    public function getLate(): ?string
    {
        return $this->late;
    }

    public function setLate(?string $late): void
    {
        $this->late = $late;
    }
}
