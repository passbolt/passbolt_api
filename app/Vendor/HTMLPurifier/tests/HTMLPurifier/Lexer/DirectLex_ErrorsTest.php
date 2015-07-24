<?php

class HTMLPurifier_Lexer_DirectLex_ErrorsTest extends HTMLPurifier_ErrorsHarness
{

    public function invoke($input)
    {
        $lexer = new HTMLPurifier_Lexer_DirectLex();
        $lexer->tokenizeHTML($input, $this->config, $this->context);
    }

    public function invokeAttr($input)
    {
        $lexer = new HTMLPurifier_Lexer_DirectLex();
        $lexer->parseAttributeString($input, $this->config, $this->context);
    }

    public function testExtractBody()
    {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Extracted body');
        $this->invoke('<body>foo</body>');
    }

    public function testUnclosedComment()
    {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Unclosed comment');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('<!-- >');
    }

    public function testUnescapedLt()
    {
        $this->expectErrorCollection(E_NOTICE, 'Lexer: Unescaped lt');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('< foo>');
    }

    public function testMissingGt()
    {
        $this->expectErrorCollection(E_WARNING, 'Lexer: Missing gt');
        $this->expectContext('CurrentLine', 1);
        $this->invoke('<a href=""');
    }

    // these are sub-errors, will only be thrown in context of collector

    public function testMissingAttributeKey1()
    {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing attribute key');
        $this->invokeAttr('=""');
    }

    public function testMissingAttributeKey2()
    {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing attribute key');
        $this->invokeAttr('foo="bar" =""');
    }

    public function testMissingEndQuote()
    {
        $this->expectErrorCollection(E_ERROR, 'Lexer: Missing end quote');
        $this->invokeAttr('src="foo');
    }

}

// vim: et sw=4 sts=4
