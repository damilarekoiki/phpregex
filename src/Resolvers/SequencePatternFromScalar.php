<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Resolvers;

use Stringable;

final readonly class SequencePatternFromScalar implements Stringable
{
    /**
     * SequencePatternFromScalar constructor.
     *
     * @param string $subject The scalar subject to match.
     * @param array<int, string> $patterns The current patterns in the sequence.
     * @param string $startingPattern The pattern used to start the sequence.
     * @param bool $startFromBeginning Whether the sequence starts from the beginning of the string.
     */
    public function __construct(private string $subject, private array $patterns, private string $startingPattern, private bool $startFromBeginning)
    {
    }
    /**
     * Convert the scalar subject into a sequence pattern fragment.
     *
     * @return string The sequence pattern fragment.
     */
    public function __toString(): string
    {
        $pattern = '';
        if ($this->patterns !== [$this->startingPattern] && $this->startFromBeginning) {
            $pattern = '.*';
        }

        return $pattern . preg_quote($this->subject, '/');
    }
}
