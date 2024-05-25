<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Generator\PermutationGeneratorService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PermutationsGeneratorTest extends TestCase
{
    #define count of test cases before launch
    private const TEST_CASES_COUNT = 4;

    private PermutationGeneratorService $permutationGeneratorService;

    protected function setUp(): void
    {
        $this->permutationGeneratorService = new PermutationGeneratorService();
    }

    #[DataProvider(methodName: 'provider')]
    public function testGenerate(array $input, array $expected): void
    {
        $result = $this->permutationGeneratorService->generate($input);

        $this->assertSame($expected, $result);
    }

    public static function provider(): iterable
    {
        $inputPath = __DIR__ . '/../../data/service/generator/input';
        $expectedPath = __DIR__ . '/../../data/service/generator/expected';

        for ($i = 1; $i <= self::TEST_CASES_COUNT; ++$i) {
            yield [
                require $inputPath . sprintf('/input%d.php', $i),
                require $expectedPath . sprintf('/output%d.php', $i)
            ];
        }
    }
}
