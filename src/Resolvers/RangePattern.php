<?php

declare(strict_types=1);

namespace Ten\Phpregex\Resolvers;

use Stringable;

final readonly class RangePattern implements Stringable
{
    /**
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end
     */
    public function __construct(
        private array $ranges,
        private bool $negated = false,
        private bool $caseSensitive = true,
    ) {
    }

    public function __toString(): string
    {
        $pattern = $this->negated ? '[^' : '[';

        foreach ($this->ranges as $subject1 => $subject2) {
            if (!$this->caseSensitive) {
                $subject1Lower = mb_strtolower((string) $subject1);
                $subject2Lower = mb_strtolower((string) $subject2);
                $subject1Upper = mb_strtoupper((string) $subject1);
                $subject2Upper = mb_strtoupper((string) $subject2);
                $pattern .= preg_quote($subject1Lower, '/') . '-' . preg_quote($subject2Lower, '/');
                $pattern .= preg_quote($subject1Upper, '/') . '-' . preg_quote($subject2Upper, '/');
            } else {
                $pattern .= preg_quote((string) $subject1, '/') . '-' . preg_quote((string) $subject2, '/');
            }
        }

        return $pattern . ']';
    }
}
