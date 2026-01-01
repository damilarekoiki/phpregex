<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Sequence;

trait Sequential
{
    public function containsExactSequencesOf(string|int $subject, int $occurences): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . "{" . $occurences . "}");
    }

    public function containsSequencesOf(string|int $subject, int $minOcurrences, int $maxOccurrences): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . "{" . $minOcurrences . "," . $maxOccurrences . "}");
    }

    public function containsAtleastSequencesOf(string|int $subject, int $minOcurrences): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . "{" . $minOcurrences . ",}");
    }

    public function sequence(Closure $callback, bool $startFromBeginning = false): self
    {
        $sequence = new Sequence($this, $startFromBeginning);
        $callback($sequence);
        $sequence->endSequence();
        return $this;
    }
}
