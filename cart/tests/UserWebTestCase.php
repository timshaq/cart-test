<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserWebTestCase extends WebTestCase
{
    protected ?KernelBrowser $client = null;
    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->loadFixtures([UserFixtures::class], ['constant']);
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
