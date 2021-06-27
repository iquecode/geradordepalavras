<?php

class WordGeneratorView extends TPage
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/word_genetaror.html');
        
//         $replaces = [];
//         $replaces['title']  = 'Panel title';
//         $replaces['footer'] = 'Panel footer';
//         $replaces['name']   = 'Someone famous';
        
        // replace the main section variables
//         $this->html->enableSection('main', $replaces);
        
        $this->html->enableSection('main');
        
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
//         $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->html);
        parent::add($vbox);            
    }
}