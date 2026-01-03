<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Quantifiers
{
    /**
     * Check if the subject contains at least one occurrence of the given characters (lookahead).
     *
     * @param string|int $subject The characters to check for.
     */
    public function containsAtleastOne(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '+)', false);
    }

    /**
     * Match at least one occurrence of the given characters (consuming).
     *
     * @param string|int $subject The characters to check for.
     */
    public function atLeastOne(string|int $subject): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . '+');
    }

    /**
     * Check if the subject contains zero or more occurrences of the given characters (lookahead).
     *
     * @param string|int $subject The characters to check for.
     */
    public function containsZeroOrMore(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '*)', false);
    }

    /**
     * Match zero or more occurrences of the given characters (consuming).
     *
     * @param string|int $subject The characters to check for.
     */
    public function zeroOrMore(string|int $subject): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . '*');
    }

    /**
     * Check if the subject contains zero or one occurrence of the given characters (lookahead).
     *
     * @param string|int $subject The characters to check for.
     */
    public function containsZeroOrOne(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '?)', false);
    }

    /**
     * Match zero or one occurrence of the given characters (consuming).
     *
     * @param string|int $subject The characters to check for.
     */
    public function zeroOrOne(string|int $subject): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . '?');
    }
}
