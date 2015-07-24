<?php

class HTMLPurifier_URIFilter_MakeAbsoluteTest extends HTMLPurifier_URIFilterHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->filter = new HTMLPurifier_URIFilter_MakeAbsolute();
        $this->setBase();
    }

    public function setBase($base = 'http://example.com/foo/bar.html?q=s#frag')
    {
        $this->config->set('URI.Base', $base);
    }

    // corresponding to RFC 2396

    public function testPreserveAbsolute()
    {
        $this->assertFiltering('http://example.com/foo.html');
    }

    public function testFilterBlank()
    {
        $this->assertFiltering('', 'http://example.com/foo/bar.html?q=s');
    }

    public function testFilterEmptyPath()
    {
        $this->assertFiltering('?q=s#frag', 'http://example.com/foo/bar.html?q=s#frag');
    }

    public function testPreserveAltScheme()
    {
        $this->assertFiltering('mailto:bob@example.com');
    }

    public function testFilterIgnoreHTTPSpecialCase()
    {
        $this->assertFiltering('http:/', 'http://example.com/');
    }

    public function testFilterAbsolutePath()
    {
        $this->assertFiltering('/foo.txt', 'http://example.com/foo.txt');
    }

    public function testFilterRelativePath()
    {
        $this->assertFiltering('baz.txt', 'http://example.com/foo/baz.txt');
    }

    public function testFilterRelativePathWithInternalDot()
    {
        $this->assertFiltering('./baz.txt', 'http://example.com/foo/baz.txt');
    }

    public function testFilterRelativePathWithEndingDot()
    {
        $this->assertFiltering('baz/.', 'http://example.com/foo/baz/');
    }

    public function testFilterRelativePathDot()
    {
        $this->assertFiltering('.', 'http://example.com/foo/');
    }

    public function testFilterRelativePathMultiDot()
    {
        $this->assertFiltering('././foo/./bar/.././baz', 'http://example.com/foo/foo/baz');
    }

    public function testFilterAbsolutePathWithDot()
    {
        $this->assertFiltering('/./foo', 'http://example.com/foo');
    }

    public function testFilterAbsolutePathWithMultiDot()
    {
        $this->assertFiltering('/./foo/../bar/.', 'http://example.com/bar/');
    }

    public function testFilterRelativePathWithInternalDotDot()
    {
        $this->assertFiltering('../baz.txt', 'http://example.com/baz.txt');
    }

    public function testFilterRelativePathWithEndingDotDot()
    {
        $this->assertFiltering('..', 'http://example.com/');
    }

    public function testFilterRelativePathTooManyDotDots()
    {
        $this->assertFiltering('../../', 'http://example.com/');
    }

    public function testFilterAppendingQueryAndFragment()
    {
        $this->assertFiltering('/foo.php?q=s#frag', 'http://example.com/foo.php?q=s#frag');
    }

    // edge cases below

    public function testFilterAbsolutePathBase()
    {
        $this->setBase('/foo/baz.txt');
        $this->assertFiltering('test.php', '/foo/test.php');
    }

    public function testFilterAbsolutePathBaseDirectory()
    {
        $this->setBase('/foo/');
        $this->assertFiltering('test.php', '/foo/test.php');
    }

    public function testFilterAbsolutePathBaseBelow()
    {
        $this->setBase('/foo/baz.txt');
        $this->assertFiltering('../../test.php', '/test.php');
    }

    public function testFilterRelativePathBase()
    {
        $this->setBase('foo/baz.html');
        $this->assertFiltering('foo.php', 'foo/foo.php');
    }

    public function testFilterRelativePathBaseBelow()
    {
        $this->setBase('../baz.html');
        $this->assertFiltering('test/strike.html', '../test/strike.html');
    }

    public function testFilterRelativePathBaseWithAbsoluteURI()
    {
        $this->setBase('../baz.html');
        $this->assertFiltering('/test/strike.html');
    }

    public function testFilterRelativePathBaseWithDot()
    {
        $this->setBase('../baz.html');
        $this->assertFiltering('.', '../');
    }

    public function testRemoveJavaScriptWithEmbeddedLink()
    {
        // credits: NykO18
        $this->setBase('http://www.example.com/');
        $this->assertFiltering('javascript: window.location = \'http://www.example.com\';', false);
    }

    // miscellaneous

    public function testFilterDomainWithNoSlash()
    {
        $this->setBase('http://example.com');
        $this->assertFiltering('foo', 'http://example.com/foo');
    }

    // error case

    public function testErrorNoBase()
    {
        $this->setBase(null);
        $this->expectError('URI.MakeAbsolute is being ignored due to lack of value for URI.Base configuration');
        $this->assertFiltering('foo/bar.txt');
    }

}

// vim: et sw=4 sts=4
