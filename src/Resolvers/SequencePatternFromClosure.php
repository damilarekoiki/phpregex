<?php
declare(strict_types=1);

namespace Ten\Phpregex\Resolvers;

use Stringable;

final readonly class SequencePatternFromClosure implements Stringable
{
    /**
     * @param array<int, string> $patterns
     */
    public function __construct(private string $patternFromClosure, private array $patterns, private string $startingPattern)
    {
    }
    public function __toString(): string
    {
        $pattern = '';
        $patternFromClosure = $this->patternFromClosure;

        $isFirst = $this->patterns === [$this->startingPattern];

        if (str_starts_with($patternFromClosure, '(?=')) {
            $patternFromClosure = substr($patternFromClosure, 3, -1);
        } elseif (!str_starts_with($patternFromClosure, '(?=') && !str_starts_with($patternFromClosure, '(?!')) {
            $pattern = '.*';
        }

        return $pattern . '(' . $patternFromClosure . ')';
    }
}
