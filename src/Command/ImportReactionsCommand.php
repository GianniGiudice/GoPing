<?php


namespace App\Command;


use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportReactionsCommand extends Command
{
    protected static $defaultName = 'app:import-reactions';
    private $entityManager;

    /**
     * ImportReactionsCommand constructor.
     * @param string|null $name
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Importing Reactions',
            '==================',
            ''
        ]);

        foreach ($this->getReactions() as $elem) {
            $reaction = new Reaction();
            $reaction->setName($elem['name']);
            $reaction->setLabel($elem['label']);
            $reaction->setClass($elem['class']);
            $reaction->setClassOk($elem['class_ok']);

            $this->entityManager->persist($reaction);

            $output->writeln([
                $elem['name'] . ' imported.'
            ]);

        }

        $this->entityManager->flush();

        $output->writeln([
            '',
            '==================',
            'Importation done.'
        ]);
    }

    private function getReactions(): array
    {
        return [
            [
                'name' => 'thumbs-up',
                'label' => 'J\'aime',
                'class' => 'far fa-thumbs-up',
                'class_ok' => 'fas fa-thumbs-up'
            ], [
                'name' => 'grin-squint-tears',
                'label' => 'Haha',
                'class' => 'far fa-grin-squint-tears',
                'class_ok' => 'fas fa-grin-squint-tears'
            ], [
                'name' => 'sad-tear',
                'label' => 'Triste',
                'class' => 'far fa-sad-tear',
                'class_ok' => 'fas fa-sad-tear'
            ]
        ];
    }
}