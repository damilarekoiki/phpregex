<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Expressions;

use DamilareKoiki\PhpRegex\Resolvers\RangePattern;

trait Positional
{
    /**
     * Match characters between specified ranges.
     *
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end.
     * @param bool $caseSensitive Whether the range check should be case sensitive.
     */
    public function between(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern((string) new RangePattern($ranges, negated: false, caseSensitive: $caseSensitive));
    }

    /**
     * Match characters not between specified ranges.
     *
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end.
     * @param bool $caseSensitive Whether the range check should be case sensitive.
     */
    public function notBetween(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern((string) new RangePattern($ranges, negated: true, caseSensitive: $caseSensitive));
    }

    /**
     * Match if the string begins with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function beginsWith(string|int $subject): self
    {
        return $this->addPattern("^" . preg_quote((string) $subject, '/'));
    }

    /**
     * Match if the string ends with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function endsWith(string|int $subject): self
    {
        return $this->addPattern('.*' . preg_quote((string) $subject, '/') . "$");
    }
}
