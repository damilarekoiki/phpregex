<?php

declare(strict_types=1);

namespace Ten\Phpregex\Resolvers;

use Stringable;

final readonly class SequencePatternFromScalar implements Stringable
{
    /**
     * @param array<int, string> $patterns
     */
    public function __construct(private string $subject, private array $patterns, private string $startingPattern, private bool $startFromBeginning)
    {
    }
    public function __toString(): string
    {
        $pattern = '';
        if ($this->patterns !== [$this->startingPattern] && $this->startFromBeginning) {
            $pattern = '.*';
        }

        return $pattern . preg_quote($this->subject, '/');
    }
}
