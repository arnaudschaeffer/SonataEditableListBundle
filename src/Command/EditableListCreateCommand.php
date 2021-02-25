<?php
namespace Aschaeffer\SonataEditableListBundle\Command;

use App\Entity\SonataEditableList;
use Aschaeffer\SonataEditableListBundle\Entity\BaseList;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class EditableListCreateCommand
 * @package App\Command
 */
class EditableListCreateCommand extends Command
{
    const FOLDER_DEFAULT = "src/Entity";

    /**
     * @var string command name
     */
    protected static $defaultName = 'sonata:editable_list:create';

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * EditableListCreateCommand constructor.
     * @param KernelInterface $appKernel
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param Reader $annotationReader
     */
    public function __construct(KernelInterface $appKernel,
                                EntityManagerInterface $em,
                                LoggerInterface $logger,
                                Reader $annotationReader)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->annotationReader = $annotationReader;
        $this->finder = new Finder();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Create editable list used in entities (use -f option to persist data)')
            ->addOption('force', '-f', InputOption::VALUE_NONE, 'Persist data')
            ->addArgument('folder', InputArgument::OPTIONAL, 'Path to entity folder')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input,
                               OutputInterface $output)
    {
        $folder = $input->getArgument('folder');
        $force = (bool)$input->getOption('force');

        if ($folder == null) {
            $folder = self::FOLDER_DEFAULT;
        }

        $this->finder->files()->in($folder)->depth(0)->name('*.php');

        if (!$this->finder->hasResults()) {
            $this->logger->warning(sprintf('No PHP files found in folder: %s', $folder));
            return;
        }

        $rep = $this->em->getRepository(SonataEditableList::class);

        foreach ($this->finder as $file) {
            $className = $file->getBasename('.php');

            $class = new \ReflectionClass("App\\Entity\\$className");
            if (!$this->annotationReader->getClassAnnotation($class, 'Doctrine\\ORM\\Mapping\\Entity')) {
                continue;
            }

            foreach ($class->getProperties() as $property) {
                $listable = $this->annotationReader->getPropertyAnnotation($property, 'App\\Annotation\\Listable');
                if (!$listable) {
                    continue;
                }

                $list = $rep->findOneBy(["code" => $listable->getCode()]);

                if ($list instanceof SonataEditableList) {
                    continue;
                }

                $list = new SonataEditableList();
                $list->setName($listable->getCode());
                $list->setCode($listable->getCode());
                $this->em->persist($list);

                $this->logger->warning(sprintf('Create new list: %s for entity %s', $listable->getCode(), $className));
                if ($force) {
                    $this->em->flush();
                }
            }
        }
    }
}