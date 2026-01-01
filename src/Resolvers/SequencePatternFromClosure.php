<?php

declare(strict_types=1);

namespace Ten\Phpregex\Resolvers;

use Stringable;

final readonly class SequencePatternFromClosure implements Stringable
{
    public function __construct(private string $patternFromClosure)
    {
    }
    public function __toString(): string
    {
        $pattern = '';
        $patternFromClosure = $this->patternFromClosure;

        if (str_starts_with($patternFromClosure, '(?=')) {
            $patternFromClosure = substr($patternFromClosure, 3, -1);
        } elseif (!str_starts_with($patternFromClosure, '(?!')) {
            $pattern = '.*';
        }

        return $pattern . '(' . $patternFromClosure . ')';
    }
}
