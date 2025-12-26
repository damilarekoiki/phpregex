<?php

namespace Ten\Phpregex;

class Sequence
{
    /**
     * @var array<int, string>
     */
    private array $patterns = [];
    public function __construct(private Regex $regex)
    {
    }

    public function then(string|int $subject): self
    {
        if($this->patterns === []) {
            $this->patterns[] = '.*';
            $this->regex->addPattern('.*');
        }
        $this->patterns[] = (string) $subject;
        $this->regex->addPattern((string) $subject);
        return $this;
    }
}