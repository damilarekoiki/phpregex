<?php

namespace Ten\Phpregex\Resolvers;

use Stringable;

class SequencePatternFromClosure implements Stringable
{
    public function __construct(private string $patternFromClosure, private array $patterns, private string $startingPattern)
    {
    }
    public function __toString(): string
    {
        $pattern = '';

        if($this->patterns != [$this->startingPattern]) {
            $pattern = '.*';
        }
        if($this->patterns === [$this->startingPattern]) {
            $this->patternFromClosure = str_replace('?=', '', $this->patternFromClosure);
        }
        $pattern .= $this->patternFromClosure;

        return $pattern;
    }
}