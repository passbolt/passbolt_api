<?php

class HTMLPurifier_Injector_PurifierLinkifyTest extends HTMLPurifier_InjectorHarness
{

    public function setup()
    {
        parent::setup();
        $this->config->set('AutoFormat.PurifierLinkify', true);
        $this->config->set('AutoFormat.PurifierLinkify.DocURL', '#%s');
    }

    public function testNoTriggerCharacer()
    {
        $this->assertResult('Foobar');
    }

    public function testTriggerCharacterInIrrelevantContext()
    {
        $this->assertResult('20% off!');
    }

    public function testPreserveNamespace()
    {
        $this->assertResult('%Core namespace (not recognized)');
    }

    public function testLinkifyBasic()
    {
        $this->assertResult(
          '%Namespace.Directive',
          '<a href="#Namespace.Directive">%Namespace.Directive</a>'
        );
    }

    public function testLinkifyWithAdjacentTextNodes()
    {
        $this->assertResult(
          'This %Namespace.Directive thing',
          'This <a href="#Namespace.Directive">%Namespace.Directive</a> thing'
        );
    }

    public function testLinkifyInBlock()
    {
        $this->assertResult(
          '<div>This %Namespace.Directive thing</div>',
          '<div>This <a href="#Namespace.Directive">%Namespace.Directive</a> thing</div>'
        );
    }

    public function testPreserveInATag()
    {
        $this->assertResult(
          '<a>%Namespace.Directive</a>'
        );
    }

    public function testNeeded()
    {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable PurifierLinkify injector because a is not allowed');
        $this->assertResult('%Namespace.Directive');
    }

}

// vim: et sw=4 sts=4
