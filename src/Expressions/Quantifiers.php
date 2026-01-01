<?php
declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Quantifiers
{
    public function containsAtleastOne(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '+)', false);
    }

    public function containsZeroOrMore(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '*)', false);
    }

    public function containsZeroOrOne(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . preg_quote((string) $subject, '/') . '?)', false);
    }
}
