<?php

namespace Ten\Phpregex;

use Closure;

class Sequence
{
    /**
     * @var array<int, string>
     */
    private array $patterns = [];
    private bool $started = false;
    private bool $ended = false;
    private string $startingPattern = '(?=.*(';
    // private string $startingPattern = '(^(';
    public function __construct(private Regex $regex, private bool $startFromBeginning = false)
    {
        if($startFromBeginning) {
            // $this->startingPattern = "^{$this->startingPattern}";
        }
        $this->startSequence();
    }

    public function then(Closure|string|int $subject): self
    {
        $pattern = '.*';

        if($subject instanceof Closure) {
            $regex = (new Regex())->build();
            $subject($regex);

            $patternFromClosure = $regex->getPattern();
            if($this->patterns === [$this->startingPattern]) {
                $patternFromClosure = str_replace('?=', '', $patternFromClosure);
            }
            $pattern .= $patternFromClosure;

        }else {
            $pattern .= preg_quote((string) $subject, '/');
        }

        $this->patterns[] = $pattern;
        $this->regex->addPattern($pattern);

        return $this;
    }

    public function startSequence(): void
    {
        if($this->started) {
            return;
        }
        $this->patterns[] = $this->startingPattern;
        $this->regex->addPattern($this->startingPattern);
        $this->started = true;
    }

    public function endSequence(): void
    {
        if($this->ended) {
            return;
        }
        $this->patterns[] = '))';
        $this->regex->addPattern('))');
        $this->ended = true;
    }
}