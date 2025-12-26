<?php

namespace Ten\Phpregex\Expressions;

trait Positional
{
    public function between(string|int $subject1, string|int $subject2, bool $caseSensitive): self
    {
        $this->patterns[] = "[" . $subject1 . "-" . $subject2 . "]";
        return $this;
    }

    public function notBetween(string|int $subject1, string|int $subject2): self
    {
        $this->patterns[] = "[^" . $subject1 . "-" . $subject2 . "]";
        return $this;
    }

    public function beginsWith(string|int $subject): self
    {
        $this->patterns[] = "^" . $subject;
        return $this;
    }

    public function endsWith(string|int $subject): self
    {
        $this->patterns[] = $subject . "$";
        return $this;
    }
}
