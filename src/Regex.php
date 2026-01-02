<?php

declare(strict_types=1);

namespace Ten\Phpregex;

use BadMethodCallException;
use Closure;
use LogicException;
use Ten\Phpregex\Expressions\Booleans;
use Ten\Phpregex\Expressions\Contains;
use Ten\Phpregex\Expressions\Exactly;
use Ten\Phpregex\Expressions\Flags;
use Ten\Phpregex\Expressions\Helpers;
use Ten\Phpregex\Expressions\Positional;
use Ten\Phpregex\Expressions\Quantifiers;
use Ten\Phpregex\Expressions\Sequential;

final class Regex
{
    use Contains;
    use Positional;
    use Quantifiers;
    use Sequential;
    use Helpers;
    use Flags;
    use Booleans;
    use Exactly;

    /**
     * @var array<int, string>
     */
    private array $patterns = [];

    /**
     * @var array<int, string>
     */
    private readonly array $magicMethods;

    private bool $wholeString = false;
    private bool $isConsuming = false;

    public function __construct()
    {
        $this->magicMethods = [
            'or',
            'and',
        ];
    }

    public static function build(bool $wholeString = false): self
    {
        $regex = new self();
        $regex->wholeString = $wholeString;
        return $regex;
    }

    public function match(string $subject): bool
    {
        return (bool) preg_match($this->resolve(), $subject);
    }

    public function count(string $subject): int
    {
        return (int) preg_match_all($this->resolve(), $subject);
    }

    public function replace(string $subject, string|Closure $replacement): string
    {
        if ($replacement instanceof Closure) {
            return (string) preg_replace_callback($this->resolve(), $replacement, $subject);
        }

        return (string) preg_replace($this->resolve(), $replacement, $subject);
    }

    public function then(string|int|Closure $subject): self
    {
        return $this->addPattern($this->resolveSimplePattern($subject));
    }

    public function addPattern(string $pattern, bool $consuming = true): self
    {
        $this->patterns[] = $pattern;
        if ($consuming) {
            $this->isConsuming = true;
        }
        return $this;
    }

    public function setPattern(string $pattern): void
    {
        $this->patterns = [$pattern];
    }

    public function __get(string $name): mixed
    {
        if (!in_array($name, $this->magicMethods)) {
            throw new LogicException("You cannot access \"{$name}\" property. Do you mean \"{$name}()\"?");
        }
        if (method_exists($this, $name) === false) {
            throw new BadMethodCallException("Method \"{$name}\" does not exist.");
        }

        return $this->{$name}();
    }

    private function resolveSimplePattern(string|int|Closure $subject): string
    {
        if ($subject instanceof Closure) {
            $regex = (new self())->build();
            $subject($regex);
            return $regex->getPattern();
        }

        return preg_quote((string) $subject, '/');
    }

    private function resolve(): string
    {
        return '/' . $this->getPattern() . '/' . implode('', array_unique($this->flags ?? []));
    }

    public function get(): string
    {
        return $this->resolve();
    }

    public function getPattern(): string
    {
        $pattern = implode('', $this->patterns);

        if ($this->wholeString) {
            if (!$this->isConsuming) {
                $pattern .= '.*';
            }
            if (!str_starts_with($pattern, '^')) {
                $pattern = '^' . $pattern;
            }
            if (!str_ends_with($pattern, '$')) {
                $pattern .= '$';
            }
        }

        return $pattern;
    }

    public function group(Closure $closure): self
    {
        $regex = Regex::build();
        $this->addPattern('(');
        $closure($regex);
        $this->addPattern($regex->getPattern());
        $this->addPattern(')');
        return $this;
    }

    /**
     * Indicates that the pattern should match the entire string
     */
    public function wholeString(): self
    {
        $this->wholeString = true;
        return $this;
    }

    public function isFirst(): bool
    {
        return $this->patterns === [];
    }
}
