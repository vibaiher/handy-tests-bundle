<?php

namespace BladeTester\HandyTestsBundle\Tests\Model;

use BladeTester\HandyTestsBundle\Model\TableTruncator;

class TableTruncatorTest extends \PHPUnit_Framework_TestCase {

  private $connection;
  private $platform;

  public function setUp()
  {
    $this->connection = $this->getMock('Doctrine\DBAL\Connection', array('getDatabasePlatform', 'query', 'executeUpdate'));
    $this->platform = $this->getMock('Doctrine\DBAL\Platforms\AbstractPlatform', array('getTruncateTableSQL'));
  }

  /**
   * @test
   */
  public function itObtainsThePlatformToTruncateTables()
  {
    // Expect
    $this->connection->expects($this->once())
                     ->method('getDatabasePlatform')
                     ->will($this->returnValue($this->platform));

    // Act
    TableTruncator::truncate(array('MyTable'), $this->connection);
  }


  /**
   * @test
   */
  public function itRemovesForeignKeyValidationsDuringTruncation()
  {
    // Expect
    $tables = array('MyTable', 'YourTable', 'HerTable');
    $this->connection->expects($this->once())
                     ->method('getDatabasePlatform')
                     ->will($this->returnValue($this->platform));

    $this->connection->expects($this->at(1))
                     ->method('query')
                     ->with($this->equalTo("SET foreign_key_checks = 0"));

    $this->connection->expects($this->at(2 + count($tables)))
                     ->method('query')
                     ->with($this->equalTo("SET foreign_key_checks = 1"));

    // Act
    TableTruncator::truncate($tables, $this->connection);
  }
}