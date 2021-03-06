<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use ArrayIterator;
use Closure;
use Generator;
use Iterator;

/**
 * @psalm-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
final class Tails extends AbstractOperation
{
    /**
     * @psalm-return Closure(Iterator<TKey, T>): Generator<int, list<T>, mixed, void>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param Iterator<TKey, T> $iterator
             *
             * @psalm-return Generator<int, list<T>, mixed, void>
             */
            static function (Iterator $iterator): Generator {
                /** @psalm-var Iterator<int, array{0: TKey, 1: T}> $iterator */
                $iterator = Pack::of()($iterator);
                $data = [...$iterator];

                while ([] !== $data) {
                    /** @psalm-var Iterator<TKey, T> $unpack */
                    $unpack = Unpack::of()(new ArrayIterator($data));

                    yield [...$unpack];

                    array_shift($data);
                }

                return yield [];
            };
    }
}
