<?php

namespace App\Command;

use App\Domain\Article\repository\ArticleRepository;
use App\Infrastructure\Search\IndexerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchCommand extends Command {
    protected static $defaultName = 'app:index';

    public function __construct(private readonly ArticleRepository $repository, private readonly IndexerInterface $indexer, private readonly NormalizerInterface $normalizer)
    {
        parent::__construct();
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input,$output);
        $articles = $this->repository->findAll();
        $io->progressStart();
        foreach($articles as $article)
        {
            $io->progressAdvance();
            $this->indexer->index($this->normalizer->normalize($article,'search'));
        }
        $io->progressFinish();

        $io->success("success");
        return 1;
    }
}