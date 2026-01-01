<?php
declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Positional
{
    public function between(string|int $subject1, string|int $subject2, bool $caseSensitive): self
    {
        return $this->addPattern("[" . preg_quote($subject1, '/') . "-" . preg_quote($subject2, '/') . "]");
    }

    public function notBetween(string|int $subject1, string|int $subject2): self
    {
        return $this->addPattern("[^" . preg_quote($subject1, '/') . "-" . preg_quote($subject2, '/') . "]");
    }

    public function beginsWith(string|int $subject): self
    {
        return $this->addPattern("^" . preg_quote($subject, '/'));
    }

    public function endsWith(string|int $subject): self
    {
        return $this->addPattern('.*' . preg_quote($subject, '/') . "$");
    }
}
