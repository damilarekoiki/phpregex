<?php

declare(strict_types=1);

namespace Ten\Phpregex;

use Closure;
use Ten\Phpregex\Resolvers\SequencePatternFromClosure;
use Ten\Phpregex\Resolvers\SequencePatternFromScalar;

final class Sequence
{
    /**
     * @var array<int, string>
     */
    private array $patterns = [];
    private bool $started = false;
    private bool $ended = false;
    private readonly string $startingPattern;
    public function __construct(private readonly Regex $regex, private readonly bool $startFromBeginning = false)
    {
        $this->startingPattern = $startFromBeginning ? "(^(" : '(?=.*(';
        $this->startSequence();
    }

    public function then(Closure|string|int $subject): self
    {
        $pattern = '';

        if ($subject instanceof Closure) {
            $regex = (new Regex())->build();
            $subject($regex);
            $patternFromClosure = $regex->getPattern();

            $pattern = new SequencePatternFromClosure($patternFromClosure, $this->patterns, $this->startingPattern);

        } else {
            $pattern = new SequencePatternFromScalar((string) $subject, $this->patterns, $this->startingPattern, $this->startFromBeginning);
        }

        $this->patterns[] = $pattern;
        $this->regex->addPattern((string) $pattern, $this->startFromBeginning);

        return $this;
    }

    public function startSequence(): void
    {
        if ($this->started) {
            return;
        }
        $this->patterns[] = $this->startingPattern;
        $this->regex->addPattern($this->startingPattern, $this->startFromBeginning);
        $this->started = true;
    }

    public function endSequence(): void
    {
        if ($this->ended) {
            return;
        }
        $this->patterns[] = '))';
        $this->regex->addPattern('))', $this->startFromBeginning);
        $this->ended = true;
    }
}
