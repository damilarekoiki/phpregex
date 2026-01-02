<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Ten\Phpregex\Resolvers\RangePattern;

trait Positional
{
    /**
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end
     */
    public function between(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern((string) new RangePattern($ranges, negated: false, caseSensitive: $caseSensitive));
    }

    /**
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end
     */
    public function notBetween(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern((string) new RangePattern($ranges, negated: true, caseSensitive: $caseSensitive));
    }

    public function beginsWith(string|int $subject): self
    {
        return $this->addPattern("^" . preg_quote((string) $subject, '/'));
    }

    public function endsWith(string|int $subject): self
    {
        return $this->addPattern('.*' . preg_quote((string) $subject, '/') . "$");
    }
}
