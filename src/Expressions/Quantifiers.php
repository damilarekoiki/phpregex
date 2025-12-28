<?php

namespace Ten\Phpregex\Expressions;

trait Quantifiers
{
    public function containsAtleastOne(string|int $subject): self
    {
        return $this->addPattern($subject . "+");
    }

    public function containsZeroOrMore(string|int $subject): self
    {
        return $this->addPattern($subject . "*");
    }

    public function containsZeroOrOne(string|int $subject): self
    {
        return $this->addPattern($subject . "?");
    }
}
