<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Sequence;

trait Sequential
{
    public function containsExactSequencesOf(string|int|Closure $subject, int $occurences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $occurences . "})", false);
    }

    public function exactSequencesOf(string|int|Closure $subject, int $occurences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $occurences . "}");
    }

    public function containsSequencesOf(string|int|Closure $subject, int $minOcurrences, int $maxOccurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $minOcurrences . "," . $maxOccurrences . "})", false);
    }

    public function sequencesOf(string|int|Closure $subject, int $minOcurrences, int $maxOccurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $minOcurrences . "," . $maxOccurrences . "}");
    }

    public function containsAtleastSequencesOf(string|int|Closure $subject, int $minOcurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern('(?=.*' . $subject . "{" . $minOcurrences . ",})", false);
    }

    public function atLeastSequencesOf(string|int|Closure $subject, int $minOcurrences): self
    {
        $subject = $this->resolveSimplePattern($subject);

        if (str_contains((string) $subject, '|') || strlen((string) $subject) > 1) {
            $subject = '(?:' . $subject . ')';
        }

        return $this->addPattern($subject . "{" . $minOcurrences . ",}");
    }

    public function sequence(Closure $callback, bool $startFromBeginning = false): self
    {
        $sequence = new Sequence($this, $startFromBeginning);
        $callback($sequence);
        $sequence->endSequence();
        return $this;
    }
}
