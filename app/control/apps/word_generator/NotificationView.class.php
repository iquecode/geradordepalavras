<?php

class NotificationView extends TWindow
{
    public function __construct()
    {
        parent::__construct();
        parent::setTitle('Window');
        
        // with: 500, height: automatic
        parent::setSize(0.6, null); // use 0.6, 0.4 (for relative sizes 60%, 40%)
        
        
        
        
        
        parent::add($this->html);            
    }
}