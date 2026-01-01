<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Positional
{
    public function between(string|int $subject1, string|int $subject2, bool $caseSensitive = true): self
    {
        if (!$caseSensitive) {
            $subject1Lower = mb_strtolower((string) $subject1);
            $subject2Lower = mb_strtolower((string) $subject2);

            $subject1Upper = mb_strtoupper((string) $subject1);
            $subject2Upper = mb_strtoupper((string) $subject2);
            return $this->addPattern("[" . preg_quote((string) $subject1Lower, '/') . "-" . preg_quote((string) $subject2Lower, '/') . preg_quote((string) $subject1Upper, '/') . "-" . preg_quote((string) $subject2Upper, '/') . "]");
        }
        return $this->addPattern("[" . preg_quote((string) $subject1, '/') . "-" . preg_quote((string) $subject2, '/') . "]");
    }

    public function notBetween(string|int $subject1, string|int $subject2, bool $caseSensitive = true): self
    {
        if (!$caseSensitive) {
            $subject1Lower = mb_strtolower((string) $subject1);
            $subject2Lower = mb_strtolower((string) $subject2);

            $subject1Upper = mb_strtoupper((string) $subject1);
            $subject2Upper = mb_strtoupper((string) $subject2);
            return $this->addPattern("[^" . preg_quote((string) $subject1Lower, '/') . "-" . preg_quote((string) $subject2Lower, '/') . preg_quote((string) $subject1Upper, '/') . "-" . preg_quote((string) $subject2Upper, '/') . "]");
        }
        return $this->addPattern("[^" . preg_quote((string) $subject1, '/') . "-" . preg_quote((string) $subject2, '/') . "]");
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
