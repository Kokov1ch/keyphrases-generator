<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Generator\GeneratorInterface;
use App\Service\Preprocessor\PreprocessorInterface;
use App\Service\Sanitizer\SanitizerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'generate-keyphrases')]
final class GenerateKeyphrasesCommand extends Command
{
    public function __construct(
        private readonly PreprocessorInterface $preprocessor,
        private readonly GeneratorInterface $generator,
        private readonly SanitizerInterface $sanitizer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->readInput();
        $words = $this->preprocessor->process($data);
        $permutations = $this->generator->generate($words);
        $result = $this->sanitizer->process($permutations);
        $phrases = $this->buildPhrases($result);
        $this->printPhrases($phrases);
        return Command::SUCCESS;
    }

    private function buildPhrases(array $permutations): array
    {
        $result = [];
        foreach ($permutations as $permutation) {
            $result[] = implode(' ', $permutation);
        }

        return $result;
    }

    private function printPhrases(array $permutations): void
    {
        foreach ($permutations as $permutation) {
            echo $permutation . "\n";
        }
    }

    private function readInput(): array
    {
        $data = [];
        while (!feof(STDIN)) {
            $data[] = explode(', ', trim(fgets(STDIN)));
        }

        return $data;
    }
}
