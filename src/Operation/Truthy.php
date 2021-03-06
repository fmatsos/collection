<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use Iterator;

/**
 * @psalm-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
final class Truthy extends AbstractOperation
{
    /**
     * @psalm-return Closure(Iterator<TKey, T>): Generator<int, bool>
     */
    public function __invoke(): Closure
    {
        $mapCallback =
            /**
             * @param mixed $value
             * @psalm-param T $value
             */
            static fn ($value): bool => (bool) $value;

        $dropWhileCallback =
            /**
             * @param mixed $value
             * @psalm-param T $value
             */
            static fn ($value): bool => true === $value;

        /** @psalm-var Closure(Iterator<TKey, T>): Generator<int, bool> $pipe */
        $pipe = Pipe::of()(
            Map::of()($mapCallback),
            DropWhile::of()($dropWhileCallback),
            Append::of()(true),
            Head::of()
        );

        // Point free style.
        return $pipe;
    }
}
