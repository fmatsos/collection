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
final class Wrap extends AbstractOperation
{
    /**
     * @psalm-return Closure(Iterator<TKey, T>): Generator<int, array<TKey, T>>
     */
    public function __invoke(): Closure
    {
        $callbackForKeys = static function (): void {
        };
        $callbackForValues =
            /**
             * @psalm-param T $initial
             * @psalm-param TKey $key
             * @psalm-param T $value
             *
             * @psalm-return array<TKey, T>
             *
             * @param mixed $initial
             * @param mixed $key
             * @param mixed $value
             */
            static function ($initial, $key, $value): array {
                return [$key => $value];
            };

        /** @psalm-var Closure(Iterator<TKey, T>): Generator<int, array<TKey, T>> $compose */
        $compose = Compose::of()(
            Associate::of()($callbackForKeys)($callbackForValues),
            Normalize::of()
        );

        // Point free style.
        return $compose;
    }
}
