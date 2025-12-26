<?php

namespace Ten\Phpregex\Expressions;

trait Quantifiers
{
    public function containsAtleastOne(string|int $subject): self
    {
        $this->patterns[] = $subject . "+";
        return $this;
    }

    public function containsZeroOrMore(string|int $subject): self
    {
        $this->patterns[] = $subject . "*";
        return $this;
    }

    public function containsZeroOrOne(string|int $subject): self
    {
        $this->patterns[] = $subject . "?";
        return $this;
    }
}
