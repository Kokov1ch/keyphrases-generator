<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Generator\GeneratorInterface;
use App\Service\Preprocessor\PreprocessorInterface;
use App\Service\Sanitizer\SanitizerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
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
        $data = $this->readInput($input);

        $words = $this->preprocessor->process($data);
        $permutations = $this->generator->generate($words);
        $result = $this->sanitizer->process($permutations);

        $phrases = $this->buildPhrases($result);
        $this->out($phrases, $output);

        return Command::SUCCESS;
    }

    private function buildPhrases(array $permutations): array
    {
        $phrases = [];
        foreach ($permutations as $permutation) {
            $phrases[] = implode(' ', $permutation);
        }

        return $phrases;
    }

    private function readInput(InputInterface $input): array
    {
        $inputStream = ($input instanceof StreamableInputInterface) ? $input->getStream() : null;
        $inputStream = $inputStream ?? STDIN;

        $inputData = stream_get_contents($inputStream);
        $lines = explode("\n", trim($inputData));

        $data = [];
        foreach ($lines as $line) {
            $data[] = explode(', ', trim($line));
        }

        return $data;
    }

    private function out(array $phrases, OutputInterface $output): void
    {
        foreach ($phrases as $phrase) {
            $output->writeln($phrase);
        }
    }
}
