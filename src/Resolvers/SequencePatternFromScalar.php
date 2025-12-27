<?php

namespace Ten\Phpregex\Resolvers;

use Stringable;

class SequencePatternFromScalar implements Stringable
{
    public function __construct(private string $subject, private array $patterns, private string $startingPattern, private bool $startFromBeginning)
    {
    }
    public function __toString(): string
    {
        $pattern = '';
        if($this->patterns !== [$this->startingPattern] && $this->startFromBeginning) {
            $pattern = '.*';
        }
        $pattern .= preg_quote((string) $this->subject, '/');

        return $pattern;
    }
}