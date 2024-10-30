<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/31/15
 * Time: 3:06 PM
 */

namespace InboundBrew\Libraries;

/**
 * Class Load
 * @package InboundBrew\Modules\Core
 */
class Load {

    /**
     * @var array
     */
    protected $fields = array();

    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * @param $view
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setView($view_name)
    {
        $view = BREW_MODULES_PATH.$view_name . ".php";
        if (!is_file($view) || !is_readable($view)) {
            throw new \InvalidArgumentException("The view '$view' is invalid.");
        }
        $this->view = $view;
        return $this;
    }
    
    /**
	get layout for view
	*/
	public function setLayout($layout_name){
		$layout = BREW_MODULES_PATH . "Core/assets/layouts/{$layout_name}.php";
		if (!is_file($layout) || !is_readable($layout)) {
            throw new \InvalidArgumentException("The layout '$layout_name' is invalid.");
        }
        $this->layout = $layout;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        if (!isset($this->fields[$name])) {
            throw new InvalidArgumentException(
            "Unable to get the field '$name'.");
        }
        $field = $this->fields[$name];
        return $field instanceof Closure ? $name($this) : $field;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * @param $name
     * @return $this
     * @throws InvalidArgumentException
     */
    public function __unset($name)
    {
        if (!isset($this->fields[$name])) {
            throw new InvalidArgumentException(
            "Unable to unset the field '$name'.");
        }
        unset($this->fields[$name]);
        return $this;
    }

    /**
     * @param null $template
     * @param array $fields
     */
    public function view($view = null, array $fields = array(),$layout_name = null)
    {
	    global $ib_dynamic_navigation;
	    $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	    $fields['navigation_modules'] = get_option(IB_TOP_NAV_VALUES);
        if ($view !== null) {
            $this->setView($view);
        }
        if($is_ajax){
	        $this->setLayout("blank");
        }else{ // use layout
	        if(!$layout_name) $layout_name = get_option(BREW_DEFAULT_LAYOUT_OPTION);
	        if(!$layout_name) $layout_name = "side_nav";
			$this->setLayout($layout_name);
        }
        
        if (!empty($fields)) {
            foreach ($fields as $name => $value) {
                $this->$name = $value;
            }
        }
		// view
        extract($this->fields);
        $active_modules = get_option(BREW_ACTIVE_MODULES_OPTION);
        ob_start();
        include $this->view;
        $content_for_layout = ob_get_clean();
        // get module based on page variable
        if(!$is_ajax && $layout_name != "blank"){
	        $page = $_GET['page'];
	        $navigation = get_option(IB_TOP_NAV_VALUES);
	        $modules = $ib_dynamic_navigation['navigation'];
	        $activeModule = array();
            foreach($modules as $index=>$item){
                if(@$item['page'] == $page){
					$activeModule = $item;
					if(!isset($module_index)) $module_index = $index;
			        break;
		        }
	        }
            if (isset($_GET['post_type']) && $_GET['post_type'] == 'ib-landing-page'){
                $activeModule = $ib_dynamic_navigation['navigation']['landing_page'];
            }
	    }
        // layout
        ob_start();
        include $this->layout;
        return ob_get_clean();
    }

    public function config($file = null)
    {
        if ($file !== null) {
            $this->setView($file);
        }
        $config = include $this->view;
        return array_to_object($config);
    }

    
}