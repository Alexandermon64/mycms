abstract class C_Controller
{
	// массив с параметрами - аналог $_GET
	protected $params;
	
	// массив с параметрами - искусственный $_GET
	protected $get;
	
	// шаблонизатор
	protected $template_maker = 'simple';

	// путь до папки с шаблонами
	protected $template_class;
	
	// Генерация внешнего шаблона
	protected abstract function render();
	
	// Функция отрабатывающая до основного метода
	protected abstract function before();
	
	public function __construct(){
		$this->template_class = new Template();
	}
	
	public function Go($action, $params, $get = array())
	{
		$this->params = $params;
		$this->get = $get;
		$this->before();
		$this->$action();
		$this->render();
	}
	
	//
	// Запрос произведен методом GET?
	//
	protected function IsGet()
	{
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}

	//
	// Запрос произведен методом POST?
	//
	protected function IsPost()
	{
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	//
	// Генерация HTML шаблона в строку.
	//
	protected function Template($fileName, $vars = array())
	{
		if($this->template_maker == 'twig'){
			return $this->template_class->twig($fileName, $vars);	
		}
		else{
			return $this->template_class->simple($fileName, $vars);
		}	
	}	
	
	// Если вызвали метод, которого нет - завершаем работу
	public function __call($name, $params){
        $this->p404();
	}
	
	public function p404(){
       $c = new C_Page();
	   $c->Go('action_404', array());
	   die();
	}
	
	public function request($url)
	{
		ob_start();
		
		if(strpos($url, 'http://') === 0 || strpos($url, 'https://'))
			echo file_get_contents($url);
		else
		{
			$rout = new Rout($url);
			$rout->Request();
		}
		
		return ob_get_clean();
	}
	
	// 
	protected function redirect($url){
	
		if($url[0] == '/')
			$url = BASE_URL . substr($url, 1);

		header("location: $url");
		exit();
	}
	
	protected function replace_widgets($str)
	{
		return preg_replace_callback(
			WIDGETS_REPLACE_PATTERN,
			create_function('$matches', 'return C_Controller::request($matches[2]);'),
			$str
		);
	}
}
