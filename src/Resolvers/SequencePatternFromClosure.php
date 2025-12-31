<?php

namespace Ten\Phpregex\Resolvers;

use Stringable;

class SequencePatternFromClosure implements Stringable
{
    /**
     * @param string $patternFromClosure
     * @param array<int, string> $patterns
     * @param string $startingPattern
     */
    public function __construct(private string $patternFromClosure, private array $patterns, private string $startingPattern)
    {
    }
    public function __toString(): string
    {
        $pattern = '';
        $patternFromClosure = $this->patternFromClosure;

        $isFirst = $this->patterns === [$this->startingPattern];

        if ($isFirst) {
            if (str_starts_with($patternFromClosure, '(?=')) {
                $patternFromClosure = substr($patternFromClosure, 3, -1);
            }
        } else {
            // Don't prepend .* if it's a lookahead
            if (!str_starts_with($patternFromClosure, '(?=') && !str_starts_with($patternFromClosure, '(?!')) {
                $pattern = '.*';
            }
        }

        $pattern .= $patternFromClosure;

        return $pattern;
    }
}