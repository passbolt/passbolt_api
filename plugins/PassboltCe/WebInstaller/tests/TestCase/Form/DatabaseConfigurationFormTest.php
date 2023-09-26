<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.2.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Form;

use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
use Cake\TestSuite\TestCase;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;

class DatabaseConfigurationFormTest extends TestCase
{
    public DatabaseConfigurationForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new DatabaseConfigurationForm();
    }

    public function tearDown(): void
    {
        unset($this->form);
        parent::tearDown();
    }

    private function getValidMysqlData(): array
    {
        return [
            'driver' => Mysql::class,
            'host' => 'localhost',
            'port' => 123,
            'username' => 'john',
            'password' => 'foo',
            'database' => 'passboltdb',
        ];
    }

    public function testDatabaseConfigurationForm_Valid()
    {
        $data = $this->getValidMysqlData();
        $this->assertTrue($this->form->validate($data));
    }

    public function testDatabaseConfigurationForm_Missing_Driver()
    {
        $data = $this->getValidMysqlData();
        unset($data['driver']);
        $this->assertFalse($this->form->validate($data));
        $this->assertTrue(is_string($this->form->getErrors()['driver']['_required']));
    }

    public function testDatabaseConfigurationForm_Invalid_Driver()
    {
        $data = $this->getValidMysqlData();
        $data['driver'] = 'foo';
        $this->assertFalse($this->form->validate($data));
        $this->assertTrue(is_string($this->form->getErrors()['driver']['inList']));
    }

    public function testDatabaseConfigurationForm_On_Mysql()
    {
        $data = $this->getValidMysqlData();
        $data['schema'] = 'bar';

        $this->assertTrue($this->form->execute($data));
        $this->assertNull($this->form->getData('schema'));
        $this->assertNull($this->form->getData('encoding'));
    }

    public function testDatabaseConfigurationForm_On_Postgres()
    {
        $data = $this->getValidMysqlData();
        $data['driver'] = Postgres::class;
        $data['schema'] = 'bar-with-dash';

        $this->assertTrue($this->form->execute($data));
        $this->assertSame('bar-with-dash', $this->form->getData('schema'));
        $this->assertSame('utf8', $this->form->getData('encoding'));
    }

    public function testDatabaseConfigurationForm_On_Postgres_Schema_Required()
    {
        $data = $this->getValidMysqlData();
        $data['driver'] = Postgres::class;
        $data['schema'] = null;

        $this->assertFalse($this->form->execute($data));
    }

    public function testDatabaseConfigurationForm_On_Postgres_Schema_Should_Be_String()
    {
        $data = $this->getValidMysqlData();
        $data['driver'] = Postgres::class;
        $data['schema'] = null;

        $this->assertFalse($this->form->execute($data));
    }
}
