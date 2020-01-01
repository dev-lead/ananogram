<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session = \Config\Services::session();
	}
        
	/**
	 * Grabs the current RendererInterface-compatible class
	 * and tells it to render the specified view with headers and footer.
         * Simply provides a convenience method that can be used in Controllers,
	 * libraries, and routed closures.
	 *
	 * NOTE: Does not provide any escaping of the data, so that must
	 * all be handled manually by the developer.
	 *
	 * @param string $name  template filename without '.php' extension
	 * @param array  $data  data to push to template file
	 * @param array  $options Unused - reserved for third-party extensions.
	 *
	 * @return string
	 */        
        public function templateView(string $name, array $data = [], array $options = []): string
	{
            $renderedFile = '';
            
            //header
            if ( is_file(APPPATH.'/Views/templates/header.php'))
            {
                $renderedFile .= view('templates/header', $data, $options);
            }
            //body
            $renderedFile .= view($name, $data, $options);
            //footer
            if ( is_file(APPPATH.'/Views/templates/footer.php'))
            {
                $renderedFile .= view('templates/footer', $data, $options);
            }
            
            return $renderedFile;
	}
}
