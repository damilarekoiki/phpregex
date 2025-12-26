<?php

namespace Ten\Phpregex\Expressions;

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
        // $this->patterns[] = "$subject{$minOcurrences , }";
        return $this;
    }
}
