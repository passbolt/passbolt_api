<?php

class HTMLPurifier_ChildDefHarness extends HTMLPurifier_ComplexHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj       = null;
        $this->func      = 'validateChildren';
        $this->to_html   = true;
        $this->to_node_list = true;
    }

}

// vim: et sw=4 sts=4
