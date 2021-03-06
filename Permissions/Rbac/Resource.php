<?php

namespace Permissions\Rbac;

use RuntimeException,
    ArrayAccess,
    Permissions\Rbac\Resource\Page,
    Permissions\Rbac\Resource\Object;

/**
 * RBAC Resource
 * 
 * @category  Rbac
 * @package   Resource
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/permissions
 */
Class Resource
{
    /**
     * Resource id
     * 
     * @var string
     */
    public $resourceId;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->c = $c;

        $this->c['rbac.resource.page'] = function () {
            return new Page($this->c);
        };
        $this->c['rbac.resource.object'] = function () {
            return new Object($this->c);
        };
    }

    /**
     * Magic methods ( Get )
     * 
     * @param string $class name
     * 
     * @return object
     */
    public function __get($class)
    {
        return $this->c['rbac.resource.'. strtolower($class)];
    }

    /**
     * Set resource id
     * 
     * @param string $id resource id
     * 
     * @return void
     */
    public function setId($id)
    {
        $this->resourceId = $id;
    }

    /**
     * Get resource id
     * 
     * @return string
     */
    public function getId()
    {
        if (empty($this->resourceId)) {
            throw new RuntimeException('Resource id is not defined. You must first use $this->rbac->resource->setId() method.');
        }
        return $this->resourceId;
    }
}


// END Resource.php File
/* End of file Resource.php

/* Location: .Obullo/Permissions/Rbac/Resource.php */
