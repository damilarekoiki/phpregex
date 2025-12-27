<?php

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Sequence;

trait Sequential
{
    public function containsExactSequencesOf(string|int $subject, int $occurences): self
    {
        $this->patterns[] = $subject . "{" . $occurences . "}";
        return $this;
    }

    public function containsSequencesOf(string|int $subject, int $minOcurrences, int $maxOccurrences): self
    {
        $this->patterns[] = $subject . "{" . $minOcurrences . "," . $maxOccurrences . "}";
        return $this;
    }

    public function containsAtleastSequencesOf(string|int $subject, int $minOcurrences): self
    {
        $this->patterns[] = $subject . "{" . $minOcurrences . ",}";
        return $this;
    }

    public function sequence(Closure $callback, bool $startFromBeginning = false): self
    {
        $sequence = new Sequence($this, $startFromBeginning);
        $callback($sequence);
        $sequence->endSequence();
        return $this;
    }
}
