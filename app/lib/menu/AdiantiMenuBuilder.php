<?php

use Adianti\Widget\Menu\TMenu;

class AdiantiMenuBuilder
{
    public static function parse($file, $theme)
    {
        switch ($theme)
        {
            
            
            case 'iquedev':


                ob_start();
                $callback = array('SystemPermission', 'checkPermission');
                $menu = THMenuBar::newFromXML($file, $callback);

//                 $menu = THMenuBar::newFromXML($file);
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;




                // ob_start();
                // $xml = new SimpleXMLElement(file_get_contents($file));
                // // $menu = new TMenu($xml, null, 1, 'drop', 'itemclass', 'linkclass', null);
                // $menu = new TMenu($xml);
                // $menu->class = 'iquedev-user-header';
                // $menu->id    = 'iquedev-user-header-menu';
                // $menu->show();
                // $menu_string = ob_get_clean();
                // return $menu_string;
                // break;  
            case 'theme3':
                ob_start();
                $xml = new SimpleXMLElement(file_get_contents($file));
                $menu = new TMenu($xml, null, 1, 'treeview-menu', 'treeview', '');
                $menu->class = 'sidebar-menu';
                $menu->id    = 'side-menu';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
            default:
                ob_start();
                $xml = new SimpleXMLElement(file_get_contents($file));
                $menu = new TMenu($xml, null, 1, 'ml-menu', 'x', 'menu-toggle waves-effect waves-block');
                
                $li = new TElement('li');
                $li->{'class'} = 'active';
                $menu->add($li);
                
                $li = new TElement('li');
                $li->add('MENU');
                $li->{'class'} = 'header';
                $menu->add($li);
                
                $menu->class = 'list';
                $menu->style = 'overflow: hidden; width: auto; height: 390px;';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
        }
    }
}