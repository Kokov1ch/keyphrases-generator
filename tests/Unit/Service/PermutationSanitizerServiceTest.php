<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Sanitizer\PermutationSanitizerService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PermutationSanitizerServiceTest extends TestCase
{
    #define count of test cases before launch
    private const TEST_CASES_COUNT = 4;

    private PermutationSanitizerService $permutationSanitizerService;

    protected function setUp(): void
    {
        $this->permutationSanitizerService = new PermutationSanitizerService();
    }


    #[DataProvider(methodName: 'provider')]
    public function testProcess(array $input, array $expected): void
    {
        $result = $this->permutationSanitizerService->process($input);

        $this->assertSame($expected, $result);
    }

    public static function provider(): iterable
    {
        $inputPath = __DIR__ . '/../../data/service/sanitizer/input';
        $expectedPath = __DIR__ . '/../../data/service/sanitizer/expected';

        for ($i = 1; $i <= self::TEST_CASES_COUNT; ++$i) {
            yield [
                require $inputPath . sprintf('/input%d.php', $i),
                require $expectedPath . sprintf('/output%d.php', $i)
            ];
        }
    }
}
