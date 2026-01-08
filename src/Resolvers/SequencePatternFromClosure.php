<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Resolvers;

use Stringable;

final readonly class SequencePatternFromClosure implements Stringable
{
    /**
     * SequencePatternFromClosure constructor.
     *
     * @param string $patternFromClosure The regex pattern generated from the closure.
     */
    public function __construct(private string $patternFromClosure)
    {
    }
    /**
     * Convert the closure pattern into a sequence pattern fragment.
     *
     * @return string The sequence pattern fragment.
     */
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
