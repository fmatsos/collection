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
final class Permutate extends AbstractOperation
{
    public function __invoke(): Closure
    {
        $getPermutations =
            /**
             * @param array<int, mixed> $dataset
             * @psalm-param list<T> $dataset
             *
             * @psalm-return Generator<int, list<T>>
             */
            fn (array $dataset): Generator => $this->getPermutations($dataset);

        return
            /**
             * @psalm-param Iterator<TKey, T> $iterator
             *
             * @psalm-return Generator<int, list<T>>
             */
            static fn (Iterator $iterator): Generator => yield from $getPermutations([...$iterator]);
    }

    /**
     * @param array<mixed> $dataset
     * @psalm-param list<T> $dataset
     *
     * @return Generator<array<mixed>>
     * @psalm-return Generator<int, list<T>>
     */
    private function getPermutations(array $dataset): Generator
    {
        foreach ($dataset as $key => $firstItem) {
            $remaining = $dataset;

            array_splice($remaining, $key, 1);

            if ([] === $remaining) {
                yield [$firstItem];

                continue;
            }

            foreach ($this->getPermutations($remaining) as $permutation) {
                array_unshift($permutation, $firstItem);

                yield $permutation;
            }
        }
    }
}
