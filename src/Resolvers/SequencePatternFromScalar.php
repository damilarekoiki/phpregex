<?php

namespace Ten\Phpregex\Resolvers;

use Stringable;

class SequencePatternFromScalar implements Stringable
{
    /**
     * @param string $subject
     * @param array<int, string> $patterns
     * @param string $startingPattern
     * @param bool $startFromBeginning
     */
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