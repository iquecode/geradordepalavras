<?php
/**
 * SystemUserForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemUserForm extends TPage
{
    protected $form; // form
    protected $program_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_System_user');
        $this->form->setFormTitle( _t('User') );
        
        // create the form fields
        $id            = new TEntry('id');
        $name          = new TEntry('name');
        $email         = new TEntry('email');
        $login         = new TEntry('login');
        $password      = new TPassword('password');
        $repassword    = new TPassword('repassword');
        $cpf           = new TEntry('cpf');
        $cep = new TEntry('cep');
        $address = new TEntry('address');
        $number        = new TEntry('number');
        $complement    = new TEntry('complement');    
        $city = new TEntry('city');
        $uf = new TDBCombo('uf', 'permission', 'State', 'uf', 'name', 'id');
        $code_transaction = new TEntry('code_transaction');
        $phone =  new TEntry('phone');
        $date_contract = new TDate('date_contract');
        $date_expiration = new TDate('date_expiration');
        $days_contract = new TCombo('days_contract');        
//         $status = new TEntry('status');
        $obs = new TText('obs');
        $days_contract_values = array();
        $days_contract_values['30']  = '30 dias';
        $days_contract_values['60']  = '60 dias';
        $days_contract_values['90']  = '90 dias';
        $days_contract_values['180'] = '180 dias';
        $days_contract_values['360'] = '360 dias';  
       
        $days_contract->addItems($days_contract_values);
        
        
        $unit_id       = new TDBCombo('system_unit_id','permission','SystemUnit','id','name');
        $groups        = new TDBCheckGroup('groups','permission','SystemGroup','id','name');
        $frontpage_id  = new TDBUniqueSearch('frontpage_id', 'permission', 'SystemProgram', 'id', 'name', 'name');
        $units         = new TDBCheckGroup('units','permission','SystemUnit','id','name');
        
        
        $date_expiration->setEditable(FALSE);
        
        $date_contract->setMask('dd/mm/yyyy');   
        $date_expiration->setMask('dd/mm/yyyy');
        $date_contract->setDataBasemask('yyyy-mm-dd');
        $date_expiration->setDataBasemask('yyyy-mm-dd');
        
        $units->setLayout('horizontal');
        if ($units->getLabels())
        {
            foreach ($units->getLabels() as $label)
            {
                $label->setSize(200);
            }
        }
        
        $groups->setLayout('horizontal');
        if ($groups->getLabels())
        {
            foreach ($groups->getLabels() as $label)
            {
                $label->setSize(200);
            }
        }
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('SystemUserList','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $id->setSize('100%');
        $name->setSize('100%');
        $email->setSize('100%');
        $login->setSize('100%');
        $password->setSize('100%');
        $repassword->setSize('100%');
        $date_contract->setSize('100%');
        $date_expiration->setSize('100%');
        
        
        $cpf->setSize('100%');
        
        $cep->setSize('100%');
        $address->setSize('100%');
//         $number        = new TEntry('number');
//         $complement    = new TEntry('complement');    
//         $city = new TEntry('city');
//         $uf = new TDBCombo('uf', 'tattini', 'State', 'uf', 'name', 'id');
//         $code_transaction = new TEntry('code_transaction');
//         $phone =  new TEntry('phone');
//         $date_contract = new TDate();
//         $date_expiration = new TDate();
//         $days_contract = new TCombo('days_contract');        
//         $status = new TEntry('status');
//         $obs = new TText('obs');
        
        
        
        $unit_id->setSize('100%');
        $frontpage_id->setSize('100%');
        $frontpage_id->setMinLength(1);
        
        // outros
        $id->setEditable(false);
        
        $cpf->setMask('999.999.999-99', true);
        $cep->setMask('99.999-999', true);
        $phone->setProperty('data-mask_ique', 'phone');
        TScript::create("maskPhone()");
        
        $cep->setProperty('auto_cep', 'cep');
        $city->setProperty('auto_cep', 'localidade');
        $address->setProperty('auto_cep', 'logradouro');
        $uf->setProperty('auto_cep', 'uf');
        TScript::create("autoCep()");
        
        // validations
        $name->addValidation(_t('Name'), new TRequiredValidator);
        $login->addValidation('Login', new TRequiredValidator);
        $email->addValidation('Email', new TEmailValidator);
        
        
      
        
        
        $this->form->addFields(  [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$name] );
        
        $this->form->addFields( [new TLabel('CPF:')],       [$cpf] );
        
        
        $this->form->addFields( [new TLabel(_t('Login'))], [$login] );
        $this->form->addFields( [new TLabel(_t('Email'))], [$email] );
        
        
        
        $this->form->addFields( [new TLabel(_t('Password'))], [$password],  [new TLabel(_t('Password confirmation'))], [$repassword] );
       
        $this->form->addFields( [new TLabel('Telefone:')],  [$phone] );
        
        $this->form->addFields([new TLabel('Plano:')],  [$days_contract] );
        
        $this->form->addFields([new TLabel('Contratação:')],  [$date_contract],
                               [new TLabel('Expiração:')],  [$date_expiration]);
        
        
     
        
        
        
        
        
//         $this->form->addFields([new TLabel('Expiração:')],  [$date_expiration] );
        $this->form->addFields([new TLabel('Cod. Transação:')],  [$code_transaction] );
//         $this->form->addFields([new TLabel('Status:')],  [$status] );
        
        $this->form->addFields( [new TFormSeparator('')] );
        $this->form->addFields( [new TLabel('CEP')],        [$cep] ); 
        $this->form->addFields( [new TLabel('Rua')],        [$address] );
        $this->form->addFields( [new TLabel('Nº')],          [$number],
                                [new TLabel('Complemento')], [$complement] );
        $this->form->addFields( [new TLabel('Cidade:')],    [$city], 
                                [new TLabel('UF:')],        [$uf]);
        
        $this->form->addFields( [new TFormSeparator('')] );
        $this->form->addFields( [new TLabel('Obs.')],        [$obs] );
        
        
//         $this->form->addFields( [new TFormSeparator(_t('Units'))] );
//         $this->form->addFields( [$units] );
//         $this->form->addFields( [new TFormSeparator(_t('Groups'))] );
//         $this->form->addFields( [$groups] );
//         $this->form->addFields( [new TLabel(_t('Main unit'))], [$unit_id],  [new TLabel(_t('Front page'))], [$frontpage_id] );
//         
//         
//         
//         
//         $this->program_list = new TCheckList('program_list');
//         $this->program_list->setIdColumn('id');
//         $this->program_list->addColumn('id',    'ID',    'center',  '10%');
//         $col_name    = $this->program_list->addColumn('name', _t('Name'),    'left',   '50%');
//         $col_program = $this->program_list->addColumn('controller', _t('Menu path'),    'left',   '40%');
//         $col_program->enableAutoHide(500);
//         $this->program_list->setHeight(150);
//         $this->program_list->makeScrollable();
//         
//         $col_name->enableSearch();
//         $search_name = $col_name->getInputSearch();
//         $search_name->placeholder = _t('Search');
//         $search_name->style = 'width:50%;margin-left: 4px; border-radius: 4px';
//         
//         $col_program->setTransformer( function($value, $object, $row) {
//             $menuparser = new TMenuParser('menu.xml');
//             $paths = $menuparser->getPath($value);
//             
//             if ($paths)
//             {
//                 return implode(' &raquo; ', $paths);
//             }
//         });
//         
//         $this->form->addFields( [new TFormSeparator(_t('Programs'))] );
//         $this->form->addFields( [$this->program_list] );
//         
//         TTransaction::open('permission');
//         $this->program_list->addItems( SystemProgram::get() );
//         TTransaction::close();
        
        
        
      
        
         
        $action = new TAction( array($this, 'onRegisterDateExpiration') );
        $date_contract->setExitAction($action);
        $days_contract->setChangeAction($action);
        
        
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'SystemUserList'));
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        try
        {
            
//             extract($param);
            //new TMessage('info', $param['uf']);
            
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new SystemUser;
            $object->fromArray( (array) $data );
            
           
            
            $senha = $object->password;
            
            if( empty($object->login) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Login')));
            }
            
            if( empty($object->id) )
            {
                if (SystemUser::newFromLogin($object->login) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this login is already registered'));
                }
                
                if (SystemUser::newFromEmail($object->email) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this e-mail is already registered'));
                }
                
                if ( empty($object->password) )
                {
                    throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Password')));
                }
                
                $object->active = 'Y';
            }
            
            if( $object->password )
            {
                if( $object->password !== $param['repassword'] )
                    throw new Exception(_t('The passwords do not match'));
                
                //$object->password = md5($object->password);
                $object->password = password_hash($object->password, PASSWORD_DEFAULT);
            }
            else
            {
                unset($object->password);
            }
            
            
            //iquedev
            $object->frontpage_id = 41; // WordGeneratorView
//             $object->uf = param['uf'];
            
            
            $object->store();
            $object->clearParts();
            
            //Group Standart
            $object->addSystemUserGroup( new SystemGroup(2)); 
            
            //admin
            if ($object->id == 1) 
            {
                $object->addSystemUserGroup( new SystemGroup(1)); 
            }
            
            
            
            
//             if( !empty($data->groups) )
//             {
//                 foreach( $data->groups as $group_id )
//                 {
//                     $object->addSystemUserGroup( new SystemGroup($group_id) );
//                 }
//             }
            
            //Unit A e Unit B
            $object->addSystemUserUnit( new SystemUnit(1) );
            $object->addSystemUserUnit( new SystemUnit(2) );
//             if( !empty($data->units) )
//             {
//                 foreach( $param['units'] as $unit_id )
//                 {
//                     $object->addSystemUserUnit( new SystemUnit($unit_id) );
//                 }
//             }
//             
//             if (!empty($data->program_list))
//             {
//                 foreach ($data->program_list as $program_id)
//                 {
//                     $object->addSystemUserProgram( new SystemProgram( $program_id ) );
//                 }
//             }
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_System_user', $data);
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
    
        
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_user
                $object = new SystemUser($key);
                
                unset($object->password);
                
                $groups = array();
                $units  = array();
                
                if( $groups_db = $object->getSystemUserGroups() )
                {
                    foreach( $groups_db as $group )
                    {
                        $groups[] = $group->id;
                    }
                }
                
                if( $units_db = $object->getSystemUserUnits() )
                {
                    foreach( $units_db as $unit )
                    {
                        $units[] = $unit->id;
                    }
                }
                
                $program_ids = array();
                foreach ($object->getSystemUserPrograms() as $program)
                {
                    $program_ids[] = $program->id;
                }
                
                $object->program_list = $program_ids;
                $object->groups = $groups;
                $object->units  = $units;
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    
    
    public static function onRegisterDateExpiration($param)
    {
        
//            
//         if ( $param['days_contract'] == ''   || $param['data_contract'] == '' ) 
//         {
//             return false;
//         }

        $days           = $param['days_contract'];        
        $array_date = explode('/', $param['date_contract']); 
        if ( count($array_date) != 3  )
        {
            return false;
        }
        if (!checkdate($array_date[1], $array_date[0], $array_date[2]))
        {
            return false;
        }
        if (!$days > 0)
        {
            return false;
        }
     
        $interval       = new DateInterval("P{$days}D");
        $expiration     = DateTime::createFromFormat('d/m/Y',$param['date_contract']);
        $expiration->add($interval);
        
        $obj = new StdClass;
        $obj->date_expiration = $expiration->format('d/m/Y');
        TForm::sendData('form_System_user', $obj);
        
        
        
        //new TMessage('info', $expiration->format('d/m/Y'));
    } 
    
    
     /**
     * Action to be executed when the user leaves the input_exit field
     */
    public static function onExitAction($param)
    {
//         $obj = new StdClass;
//         $obj->response_a = 'Resp. for '.$param['input_exit'].' at ' . date('H:m:s');
//         $obj->combo_change = 'a';
//         
//         TForm::sendData('form_interaction', $obj);
        
//         new TMessage('info', 'TESTE');
    }
    
    
    
}
