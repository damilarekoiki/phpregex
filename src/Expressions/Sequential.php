<?php

namespace Ten\Phpregex\Expressions;

trait Sequential
{
    public function containsExactSequencesOf($subject, $occurences)
    {
        $this->patterns[] = "$subject{$occurences}";
        return $this;
    }

    public function containsSequencesOf($subject, $minOcurrences, $maxOccurrences)
    {
        $this->patterns[] = "$subject{ $minOcurrences,$maxOccurrences }";
        return $this;
    }

    public function containsAtleastSequencesOf($subject, $minOcurrences)
    {
        // $this->patterns[] = "$subject{$minOcurrences , }";
        return $this;
    }
}
