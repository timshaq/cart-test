<?php

namespace App\Tests;

use App\Entity\Constant;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebTestCaseWithFixtures extends WebTestCase
{
    protected ?KernelBrowser $client = null;
    protected ?EntityManagerInterface $entityManager = null;
    protected ?ReferenceRepository $referenceRepository = null;
    protected array $fixturesDependencies = [];
    protected array $excludedTables = [Constant::TABLE_NAME];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // todo: refactor it (move to FixturesWebTestCase)
        $this->loadFixtures($this->fixturesDependencies, $this->excludedTables);
    }

    protected function loadFixtures(array $fixtures, array $excludedTables = []): void
    {
        $loader = new Loader();
        foreach ($fixtures as $fixtureClass) {
            $fixture = static::getContainer()->get($fixtureClass);
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger($this->entityManager, $excludedTables);
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
        $this->referenceRepository = $executor->getReferenceRepository();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}
