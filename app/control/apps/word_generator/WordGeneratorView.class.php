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
             
             
             
             
             
             
             
             
             
         
         $date_diff = $today->diff($expiration);
         $days = $date_diff->format('%a');
         
         $days = ($today<=$expiration) ?  $days  : -1;
         
         $msg ='';
         $color = 'red';
        
         if ( $days < 0)
         {
             $msg = 'Plano Expirado!';    
         }   
         
         if ( $days == 0)
         {
             $msg = 'Plano expira Hoje!';
         }   
         
         if ( $days ==1)
         {
             $msg = 'Seu plano expira amanhã';
         }   
         
         if ( $days > 1 && $days <= 5)
         {
             $msg = 'Seu plano expira em apenas ' . $days . ' dias.' ;
             $color = '#E65100';
         }   
         
         if ( $days > 5)
         {
             $msg = 'Você ainda tem ' . $days . ' dias de plano ativo.' ;
             $color = '#1A237E';
         }   
         
         if ( empty(TSession::getValue('date_expiration')) ) 
         {
             $msg ='';
         }  
        
      
        $html2 = new THtmlRenderer('app/resources/word_generation_renovation.html');
        
        $replaces = [];
        $replaces['msg'] = $msg;
        $replaces['color'] = $color;
        $html2->enableSection('main', $replaces);
             
             
             
        
        
            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            
            
            if ( $days<=5 && !empty(TSession::getValue('date_expiration')))
            {
                $vbox->add($html2);
            }
            
            
            
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