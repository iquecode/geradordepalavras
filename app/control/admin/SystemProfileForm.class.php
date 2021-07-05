<?php
/**
 * SystemProfileForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemProfileForm extends TPage
{
    private $form;
    
    
    
    public function __construct()
    {
        parent::__construct();
        
        
        $this->form = new BootstrapFormBuilder('profile_form');
        $this->form->setFormTitle(_t('Profile'));
        $this->form->setClientValidation(true);
        $this->form->enableCSRFProtection();
        
        
        
        // create the form fields
        //$id            = new TEntry('id');
        $name          = new TEntry('name');
        $email         = new TEntry('email');
        $login         = new TEntry('login');
        $password1     = new TPassword('password1');
        $password2     = new TPassword('password2');
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
        $days_contract_values = array();
        $days_contract_values['30']  = '30 dias';
        $days_contract_values['60']  = '60 dias';
        $days_contract_values['90']  = '90 dias';
        $days_contract_values['180'] = '180 dias';
        $days_contract_values['360'] = '360 dias';  
       
        $days_contract->addItems($days_contract_values);      
        $date_expiration->setEditable(FALSE);
        $date_contract->setEditable(FALSE);
        $code_transaction->setEditable(FALSE);
        $days_contract->setEditable(FALSE);
        
        $date_contract->setMask('dd/mm/yyyy');   
        $date_expiration->setMask('dd/mm/yyyy');
        $date_contract->setDataBasemask('yyyy-mm-dd');
        $date_expiration->setDataBasemask('yyyy-mm-dd');
        
       
        $name->setEditable(FALSE);
        $email->setEditable(FALSE);
        $login->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        
       
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Back'), new TAction(array('WordGeneratorView','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $name->setSize('100%');
        $email->setSize('100%');
        $login->setSize('100%');
        $password1->setSize('100%');
        $password2->setSize('100%');
        $date_contract->setSize('100%');
        $date_expiration->setSize('100%');
        
        
        $cpf->setSize('100%');
        
        $cep->setSize('100%');
        $address->setSize('100%');
        
      
        
        // outros
        
        
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
        
        
      
        
        
       
        $this->form->addFields( [new TLabel('Nome')], [$name] );
        
        $this->form->addFields( [new TLabel('CPF:')],       [$cpf] );
        
        
        $this->form->addFields( [new TLabel(_t('Login'))], [$login] );
        $this->form->addFields( [new TLabel(_t('Email'))], [$email] );
        
        
        
        $this->form->addFields( [new TLabel(_t('Password'))], [$password1],  [new TLabel(_t('Password confirmation'))], [$password2] );
       
        $this->form->addFields( [new TLabel('Telefone:')],  [$phone] );
        
        $this->form->addFields([new TLabel('Plano:')],  [$days_contract] );
        
        $this->form->addFields([new TLabel('Contratação:')],  [$date_contract],
                               [new TLabel('Expiração:')],  [$date_expiration]);
        
        
     
        
        
        $this->form->addFields([new TLabel('Cod. Transação:')],  [$code_transaction] );
        
        //$this->form->addContent(['<h4>teste</h4>']);

        
        $this->form->addFields( [new TFormSeparator('')] );
        $this->form->addFields( [new TLabel('CEP')],        [$cep] ); 
        $this->form->addFields( [new TLabel('Rua')],        [$address] );
        $this->form->addFields( [new TLabel('Nº')],          [$number],
                                [new TLabel('Complemento')], [$complement] );
        $this->form->addFields( [new TLabel('Cidade:')],    [$city], 
                                [new TLabel('UF:')],        [$uf]);
        
        $this->form->addFields( [new TFormSeparator('')] );

        
         $expiration = new DateTime( TSession::getValue('date_expiration') );
         $today = new DateTime(date('Y-m-d')); 
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
        
        
        
        $container = new TVBox;
        $container->style = 'width: 100%';
      
        $html = new THtmlRenderer('app/resources/word_generation_renovation.html');
        
        $replaces = [];
        $replaces['msg'] = $msg;
        $replaces['color'] = $color;
        $html->enableSection('main', $replaces);
    
    
         if ( !empty(TSession::getValue('date_expiration')) ) 
         {
             $container->add($html); 
         }  
        
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
        
        
        
    }
    
    public function onEdit($param)
    {
        try
        {
        
           // new TMessage('info', var_dump($param));
            
            TTransaction::open('permission');
            $login = SystemUser::newFromLogin( TSession::getValue('login') );
            $this->form->setData($login);
            TSession::setValue('date_expiration', $login->date_expiration);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function onSave($param)
    {
        try
        {
        
        
//             $data_session = $_SESSION;
//             
//             new TMessage('info', var_dump($data_session));
        
            $this->form->validate();
            
            $object = $this->form->getData();
            
            TTransaction::open('permission');
            $user = SystemUser::newFromLogin( TSession::getValue('login') );
            $user->name = $object->name;
            $user->email = $object->email;
            
            $user->login       = $object->login;
            $user->cpf         = $object->cpf ;
            $user->cep         = $object->cep;
            $user->address     = $object->address;
            $user->number      = $object->number;
            $user->complement  = $object->complement;
            $user->city        = $object->city;
            $user->uf          = $object->uf;
            $user->phone       = $object->phone;
            
            
            TSession::setValue('username', $user->name);
            TSession::setValue('usermail', $user->email);
            
            TSession::setValue('login', $user->login);
          
            
            if( $object->password1 )
            {
                if( $object->password1 != $object->password2  )
                {
                    throw new Exception(_t('The passwords do not match'));
                }
                
                //$user->password = md5($object->password1);
                $user->password = password_hash($object->password1, PASSWORD_DEFAULT);
            }
            else
            {
                unset($user->password);
            }
            
            $user->store();
            
            
            $this->form->setData($object);
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}