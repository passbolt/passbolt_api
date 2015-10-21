<?php

namespace Gaufrette\Functional\Adapter;

/**
 * Functional tests for the GoogleCloudStorage adapter.
 *
 * Copy the ../adapters/GoogleCloudStorage.php.dist to GoogleCloudStorage.php and
 * adapt to your needs.
 *
 * @author  Patrik Karisch <patrik@karisch.guru>
 */
class GoogleCloudStorageTest extends FunctionalTestCase
{
    /**
     * @test
     * @group functional
     *
     * @expectedException \RuntimeException
     */
    public function shouldThrowExceptionIfBucketMissing()
    {
        /** @var \Gaufrette\Adapter\GoogleCloudStorage $adapter */
        $adapter = $this->filesystem->getAdapter();
        $oldBucket = $adapter->getOptions();
        $adapter->setBucket('Gaufrette-' . mt_rand());

        $adapter->read('foo');
        $adapter->setBucket($oldBucket);
    }

    /**
     * @test
     * @group functional
     */
    public function shouldWriteAndReadWithDirectory()
    {
        /** @var \Gaufrette\Adapter\GoogleCloudStorage $adapter */
        $adapter = $this->filesystem->getAdapter();
        $oldOptions = $adapter->getOptions();
        $adapter->setOptions(array('directory' => 'Gaufrette'));

        $this->assertEquals(12, $this->filesystem->write('foo', 'Some content'));
        $this->assertEquals(13, $this->filesystem->write('test/subdir/foo', 'Some content1', true));

        $this->assertEquals('Some content', $this->filesystem->read('foo'));
        $this->assertEquals('Some content1', $this->filesystem->read('test/subdir/foo'));

        $this->filesystem->delete('foo');
        $this->filesystem->delete('test/subdir/foo');
        $adapter->setOptions($oldOptions);
    }
}
