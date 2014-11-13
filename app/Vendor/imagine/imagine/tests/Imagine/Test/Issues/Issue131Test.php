<?php

namespace Imagine\Test\Issues;

use Imagine\Imagick\Imagine as ImagickImagine;
use Imagine\Gmagick\Imagine as GmagickImagine;
use Imagine\Exception\RuntimeException;

class Issue131Test extends \PHPUnit_Framework_TestCase
{

    private function getTemporaryDir()
    {
        $tempDir = tempnam(sys_get_temp_dir(), 'imagine');

        unlink($tempDir);
        mkdir($tempDir);

        return $tempDir;
    }

    private function getDirContent($dir)
    {
        $filenames = array();

        foreach (new \DirectoryIterator($dir) as $fileinfo) {
            if ($fileinfo->isFile()) {
                $filenames[] = $fileinfo->getPathname();
            }
        }

        return $filenames;
    }

    private function getImagickImagine($file)
    {
        try {
            $imagine = new ImagickImagine();
            $image = $imagine->open($file);
        } catch (RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        return $image;
    }

    private function getGmagickImagine($file)
    {
        try {
            $imagine = new GmagickImagine();
            $image = $imagine->open($file);
        } catch (RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        return $image;
    }

    public function testShouldSaveOneFileWithImagick()
    {
        $dir = realpath($this->getTemporaryDir());
        $targetFile = $dir . '/myfile.png';

        $imagine = $this->getImagickImagine(__DIR__ . '/multi-layer.psd');

        $imagine->save($targetFile);

        if ( ! $this->probeOneFileAndCleanup($dir, $targetFile)) {
            $this->fail('Imagick failed to generate one file');
        }
    }

    public function testShouldSaveOneFileWithGmagick()
    {
        $dir = realpath($this->getTemporaryDir());
        $targetFile = $dir . '/myfile.png';

        $imagine = $this->getGmagickImagine(__DIR__ . '/multi-layer.psd');

        $imagine->save($targetFile);

        if ( ! $this->probeOneFileAndCleanup($dir, $targetFile)) {
            $this->fail('Gmagick failed to generate one file');
        }
    }

    private function probeOneFileAndCleanup($dir, $targetFile)
    {
        $retval = true;
        $files = $this->getDirContent($dir);
        $retval = $retval && count($files) === 1;
        $file = current($files);
        $retval = $retval && $targetFile === $file;

        foreach ($files as $file) {
            unlink($file);
        }

        rmdir($dir);

        return $retval;
    }
}
