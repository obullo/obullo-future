<?php

/**
 * Jelly Elements Interface
 * 
 * @category  Jelly
 * @package   Elements
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/jelly
 */
interface Jelly_View_Elements_ElementsInterface
{
    /**
     * Constructor
     * 
     * @param array $c container
     */
    public function __construct($c);

    /**
     * Render
     * 
     * @param object $view View object
     * 
     * @return string
     */
    public function render(Jelly_View_View $view);
}

// END ElementsInterface Class
/* End of file ElementsInterface.php */

/* Location: .Obullo/Jelly/ElementsInterface.php */