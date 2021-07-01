<?php

class WordGeneratorView extends TPage
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        try
        {
            TTransaction::open('permission');
            
            $user= SystemUser::newFromLogin(TSession::getValue('login'));
            $expiration = new DateTime($user->date_expiration);
            $today = new DateTime(date('Y-m-d'));
            
            if ( $expiration >= $today ) 
            {
                 $this->html = new THtmlRenderer('app/resources/word_genetaror.html');
            }
            else
            {
                $this->html = new THtmlRenderer('app/resources/word_genetaror_expiration.html');
            }
            
            
             $this->html->enableSection('main');
        
        
            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $vbox->add($this->html);
            parent::add($vbox);    
            
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }  
    }
    
    public function onReload()
    {
    
    
    }
    
}