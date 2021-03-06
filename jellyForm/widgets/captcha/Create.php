<?php 

 namespace Widgets\Captcha;

/**
 * $app Captcha Create
 * 
 * @var Controller
 */
class Create extends \Controller
{

	/**
	* Loader
	*
	* @return void
	*/
	public function load()
	{ 
        $this->c['captcha']; 
	}


	public function index()
	{
	

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        $this->captcha->setDriver('secure');  // or set to "cool" with no background
        $this->captcha->setPool('alpha');
        $this->captcha->setChar(5);
        $this->captcha->setFontSize(15);
        $this->captcha->setHeight(25);
        $this->captcha->setWave(false);
        $this->captcha->setColor(array('red','black','blue'));
        $this->captcha->setNoiseColor(array('red','black','blue'));
        $this->captcha->setFont('NightSkK');
        $this->captcha->create();
    
	}


/* End of file create.php */
/* Location: .public/widgets/captcha/controller/create.php */
}