<?php
class State extends TRecord
{

    const TABLENAME = 'state';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial';
    
    
    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);   
            
        parent::addAttribute('name');    
        parent::addAttribute('uf');
    }
}
