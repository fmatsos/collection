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
final class Apply extends AbstractOperation
{
    /**
     * @psalm-return Closure(callable(T , TKey ): bool ...):Closure (Iterator<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param callable(T, TKey):(bool) ...$callbacks
             *
             * @psalm-return Closure(Iterator<TKey, T>): Generator<TKey, T>
             */
            static fn (callable ...$callbacks): Closure =>
                /**
                 * @psalm-param Iterator<TKey, T> $iterator
                 *
                 * @psalm-return Generator<TKey, T>
                 */
                static function (Iterator $iterator) use ($callbacks): Generator {
                    foreach ($iterator as $key => $value) {
                        foreach ($callbacks as $callback) {
                            if (true === $callback($value, $key)) {
                                continue;
                            }

                            break;
                        }

                        yield $key => $value;
                    }
                };
    }
}
