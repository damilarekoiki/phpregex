<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

trait Sequential
{
    /**
     * Check if the subject contains an exact number of sequences of the given subject (lookahead).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $occurences The exact number of occurrences.
     */
    public function containsExactSequencesOf(string|int|Closure $subject, int $occurences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $occurences . "})", false);
    }

    /**
     * Match an exact number of sequences of the given subject (consuming).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $occurences The exact number of occurrences.
     */
    public function exactSequencesOf(string|int|Closure $subject, int $occurences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $occurences . "}");
    }

    /**
     * Check if the subject contains a range of sequences of the given subject (lookahead).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $minOcurrences The minimum number of occurrences.
     * @param int $maxOccurrences The maximum number of occurrences.
     */
    public function containsSequencesOf(string|int|Closure $subject, int $minOcurrences, int $maxOccurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $minOcurrences . "," . $maxOccurrences . "})", false);
    }

    /**
     * Match a range of sequences of the given subject (consuming).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $minOcurrences The minimum number of occurrences.
     * @param int $maxOccurrences The maximum number of occurrences.
     */
    public function sequencesOf(string|int|Closure $subject, int $minOcurrences, int $maxOccurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $minOcurrences . "," . $maxOccurrences . "}");
    }

    /**
     * Check if the subject contains at least a certain number of sequences of the given subject (lookahead).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $minOcurrences The minimum number of occurrences.
     */
    public function containsAtleastSequencesOf(string|int|Closure $subject, int $minOcurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $minOcurrences . ",})", false);
    }

    /**
     * Match at least a certain number of sequences of the given subject (consuming).
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to match.
     * @param int $minOcurrences The minimum number of occurrences.
     */
    public function atLeastSequencesOf(string|int|Closure $subject, int $minOcurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $minOcurrences . ",}");
    }

    /**
     * Start a sequential pattern building block.
     *
     * @param Closure(Sequence $sequence): mixed $callback A closure that defines the sequence parts.
     * @param bool $startFromBeginning Whether the sequence must start from the beginning of the search.
     */
    public function containsSequence(Closure $callback, bool $startFromBeginning = false): self
    {
        $sequence = new Sequence($this, $startFromBeginning);
        $callback($sequence);
        $sequence->endSequence();
        return $this;
    }
}
