<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Preprocessor\PreprocessorService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PreprocessorServiceTest extends TestCase
{
    #define count of test cases before launch
    private const TEST_CASES_COUNT = 4;

    private PreprocessorService $preprocessorService;

    protected function setUp(): void
    {
        $this->preprocessorService = new PreprocessorService();
    }


    #[DataProvider(methodName: 'provider')]
    public function testProcess(array $input, array $expected): void
    {
        $result = $this->preprocessorService->process($input);

        $this->assertSame($expected, $result);
    }

    public static function provider(): iterable
    {
        $inputPath = __DIR__ . '/../../data/service/preprocessor/input';
        $expectedPath = __DIR__ . '/../../data/service/preprocessor/expected';

        for ($i = 1; $i <= self::TEST_CASES_COUNT; ++$i) {
            yield [
                require $inputPath . sprintf('/input%d.php', $i),
                require $expectedPath . sprintf('/output%d.php', $i)
            ];
        }
    }
}
