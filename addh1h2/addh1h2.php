<?php
if (!defined('_PS_VERSION_')) {
  exit;
}
 
class addh1h2 extends Module
{
  public function __construct()
  {
    $this->name = 'addh1h2';
    $this->tab = 'seo';
    $this->version = '1.0.0';
    $this->author = 'Akaronte';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('addh1h2');
    $this->description = $this->l('Añadir h1 h2 al index de prestashop.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
	   }

  public function install(){

	if (Shop::isFeatureActive())
    Shop::setContext(Shop::CONTEXT_ALL);

	return parent::install() &&
        $this->registerHook('header');
 

	  if (!parent::install()) {
	    return false;
	  }
	 //return true;
	 return parent::install() &&
        $this->registerHook('header');
	}

	public function uninstall()
	{
	  if (!parent::uninstall() ) {
	    return false;
	  }
	 
	  return true;
	}

	public function hookDisplayHeader(){
	return $this->display(__FILE__, 'views/templates/hook/addh1h2.tpl');
	}

	public function getContent()
	{
	    $output = null;
	 
	    if (Tools::isSubmit('submit'.$this->name))
	    {
	        $h1_value = strval(Tools::getValue('H1_VALUE'));
	        $h2_value = strval(Tools::getValue('H2_VALUE'));
	        if (!$h1_value || empty($h1_value) || !Validate::isGenericName($h1_value) || !$h2_value || empty($h2_value) || !Validate::isGenericName($h2_value))
	            $output .= $this->displayError($this->l('Invalid Configuration value'));
	        else
	        {
	            Configuration::updateValue('H1_VALUE', $h1_value);
	            Configuration::updateValue('H2_VALUE', $h2_value);
	            $output .= $this->displayConfirmation($this->l('Settings updated'));
	        }
	    }
	    return $output.$this->displayForm();
	}

	public function displayForm()
	{
	    // Get default language
	    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	     
	    // Init Fields form array
	    $fields_form[0]['form'] = array(
	        'legend' => array(
	            'title' => $this->l('H1 H2 VALUES'),
	        ),
	        'input' => array(
	            array(
	                'type' => 'text',
	                'label' => $this->l('H1 value'),
	                'name' => 'H1_VALUE',
	                'size' => 20,
	                'required' => true
	            ),
	            array(
	                'type' => 'text',
	                'label' => $this->l('H2 value'),
	                'name' => 'H2_VALUE',
	                'size' => 20,
	                'required' => true
	            )
	        ),

	        'submit' => array(
	            'title' => $this->l('Save'),
	            'class' => 'btn btn-default pull-right'
	        )
	    );


	     
	    $helper = new HelperForm();
	     
	    // Module, token and currentIndex
	    $helper->module = $this;
	    $helper->name_controller = $this->name;
	    $helper->token = Tools::getAdminTokenLite('AdminModules');
	    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
	     
	    // Language
	    $helper->default_form_language = $default_lang;
	    $helper->allow_employee_form_lang = $default_lang;
	     
	    // Title and toolbar
	    $helper->title = $this->displayName;
	    $helper->show_toolbar = true;        // false -> remove toolbar
	    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
	    $helper->submit_action = 'submit'.$this->name;
	    $helper->toolbar_btn = array(
	        'save' =>
	        array(
	            'desc' => $this->l('Save'),
	            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
	            '&token='.Tools::getAdminTokenLite('AdminModules'),
	        ),
	        'back' => array(
	            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
	            'desc' => $this->l('Back to list')
	        )
	    );
	     
	    // Load current value
	    $helper->fields_value['H1_VALUE'] = Configuration::get('H1_VALUE');
	    $helper->fields_value['H2_VALUE'] = Configuration::get('H2_VALUE');
	     
	    return $helper->generateForm($fields_form);
	}
}