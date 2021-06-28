<?php
class UserControlLogin
{ 
    
     public static function Store($u)
    {
        TTransaction::open('permission');
        $user = new SystemUser($u->id);
        $user->last_try_login = $u->last_try_login;
        $user->try_login = $u->try_login;
        $user->login_block = $u->login_block;
        $user->store();
        TTransaction::close(); // Closes the transaction 
    }
    
} 
