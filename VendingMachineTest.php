<?php

require 'VendingMachine.php';

use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase {
    private $vendingMachine;

    protected function setUp(): void
    {
        $this->vendingMachine = new VendingMachine();
        $this->vendingMachine->addArticle(new Article("Smarlies", "A01", 10, 1.60));
        $this->vendingMachine->addArticle(new Article("Carampar", "A02", 5, 0.60));
        $this->vendingMachine->addArticle(new Article("Avril", "A03", 2, 2.10));
        $this->vendingMachine->addArticle(new Article("KokoKola", "A04", 1, 2.95));
    }

    private function replaceArticles()
    {
        $this->vendingMachine = new VendingMachine();
        $this->vendingMachine->addArticle(new Article("Smarlies", "A01", 100, 1.60));
        $this->vendingMachine->addArticle(new Article("Carampar", "A02", 50, 0.60));
        $this->vendingMachine->addArticle(new Article("Avril", "A03", 20, 2.10));
        $this->vendingMachine->addArticle(new Article("KokoKola", "A04", 10, 2.95));
    }

    public function testFirstCase()
    {
        $this->vendingMachine->insert(3.40);
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->assertEquals(1.80, round($this->vendingMachine->getChange(), 2));
    }

    public function testSecondCase()
    {
        $this->vendingMachine->insert(2.10);
        $this->assertEquals("Vending Avril", $this->vendingMachine->choose("A03"));
        $this->assertEquals(0.00, $this->vendingMachine->getChange());
        $this->assertEquals(2.10, $this->vendingMachine->getBalance());
    }

    public function testThirdCase()
    {
        $this->assertEquals("Not enough money!", $this->vendingMachine->choose("A01"));
    }

    public function  testFourthCase()
    {
        $this->vendingMachine->insert(1.00);
        $this->assertEquals("Not enough money!", $this->vendingMachine->choose("A01"));
        $this->assertEquals(1.00, round($this->vendingMachine->getChange(), 2));
        $this->assertEquals("Vending Carampar", $this->vendingMachine->choose("A02"));
        $this->assertEquals(0.40, round($this->vendingMachine->getChange(), 2));
    }

    public function testFifthCase()
    {
        $this->vendingMachine->insert(1.00);
        $this->assertEquals("Invalid selection!", $this->vendingMachine->choose("A05"));
    }

    public function testSixthCase()
    {
        $this->vendingMachine->insert(6.00);
        $this->assertEquals("Vending KokoKola", $this->vendingMachine->choose("A04"));
        $this->assertEquals("Item KokoKola: Out of stock!", $this->vendingMachine->choose("A04"));
        $this->assertEquals(3.05, $this->vendingMachine->getChange());
    }

    public function testSeventhCase()
    {
        $this->vendingMachine->insert(6.00);
        $this->assertEquals("Vending KokoKola", $this->vendingMachine->choose("A04"));
        $this->vendingMachine->insert(6.00);
        $this->assertEquals("Item KokoKola: Out of stock!", $this->vendingMachine->choose("A04"));
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->assertEquals("Vending Carampar", $this->vendingMachine->choose("A02"));
        $this->assertEquals("Vending Carampar", $this->vendingMachine->choose("A02"));
        $this->assertEquals(6.25, round($this->vendingMachine->getChange(), 2));
        $this->assertEquals(5.75, $this->vendingMachine->getBalance());
    }

    public function testExtension()
    {
        $this->replaceArticles();

        $this->vendingMachine->insert(1000.00);
        $this->vendingMachine->setTime("2020-01-01T20:30:00");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->vendingMachine->setTime("2020-03-01T23:30:00");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->vendingMachine->setTime("2020-03-04T09:22:00");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->vendingMachine->setTime("2020-04-01T23:00:00");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->vendingMachine->setTime("2020-04-01T23:59:59");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $this->vendingMachine->setTime("2020-04-04T09:12:00");
        $this->assertEquals("Vending Smarlies", $this->vendingMachine->choose("A01"));
        $peakHours = $this->vendingMachine->getPeakHours();
        $expectedPeakHours = [
            "Hour 23 generated a revenue of 4.80",
            "Hour 9 generated a revenue of 3.20",
            "Hour 20 generated a revenue of 1.60"
        ];

        $this->assertEquals($expectedPeakHours, $peakHours);
    }

}

