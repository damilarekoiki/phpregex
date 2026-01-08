<?php

declare(strict_types=1);

namespace Ten\Phpregex\Resolvers;

use Exception;
use Stringable;

final readonly class RangePattern implements Stringable
{
    /**
     * RangePattern constructor.
     *
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end.
     * @param bool $negated Whether the range should be negated (e.g., [^a-z]).
     * @param bool $caseSensitive Whether the range check should be case sensitive.
     */
    public function __construct(
        private array $ranges,
        private bool $negated = false,
        private bool $caseSensitive = true,
    ) {
    }

    /**
     * Convert the range into a regex pattern fragment.
     *
     * @return string The regex range pattern.
     */
    public function __toString(): string
    {
        $pattern = $this->negated ? '[^' : '[';

        foreach ($this->ranges as $subject1 => $subject2) {
            if (ctype_alpha((string) $subject1) && !ctype_alpha((string) $subject2)) {
                throw new Exception("Range end '$subject2' must be a letter because range start '$subject1' is a letter.");
            }

            if (ctype_digit((string) $subject1) && !ctype_digit((string) $subject2)) {
                throw new Exception("Range end '$subject2' must be a digit because range start '$subject1' is a digit.");
            }

            if (!$this->caseSensitive) {
                $subject1Lower = mb_strtolower((string) $subject1);
                $subject2Lower = mb_strtolower((string) $subject2);
                $subject1Upper = mb_strtoupper((string) $subject1);
                $subject2Upper = mb_strtoupper((string) $subject2);
                $pattern .= preg_quote($subject1Lower, '/') . '-' . preg_quote($subject2Lower, '/');

                if ($subject1Lower !== $subject1Upper || $subject2Lower !== $subject2Upper) {
                    $pattern .= preg_quote($subject1Upper, '/') . '-' . preg_quote($subject2Upper, '/');
                }
            } else {
                $pattern .= preg_quote((string) $subject1, '/') . '-' . preg_quote((string) $subject2, '/');
            }
        }

        return $pattern . ']';
    }
}
