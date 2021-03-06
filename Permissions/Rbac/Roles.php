<?php

namespace Permissions\Rbac;

use Obullo\Tree\Db,
    Permissions\Rbac\User,
    Permissions\Rbac\Utils,
    Permissions\Rbac\Model\Roles as ModelRoles;

/**
 * Roles
 * 
 * @category  Rbac
 * @package   Roles
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/tree
 * 
 * What is the Rbac?
 * @see       https://www.sans.org/reading-room/whitepapers/sysadmin/role-based-access-control-nist-solution-1270
 *
 * http://citeseerx.ist.psu.edu/viewdoc/download?doi=10.1.1.84.9866&rep=rep1&type=pdf
 */
Class Roles
{
    /**
     * Cache constants
     * Redis supported key format.
     */
    const CACHE_GET_ROLES       = 'Permissions:Rbac:Roles:getRoles'; 
    const CACHE_GET_USERS       = 'Permissions:Rbac:Roles:getUsers';
    const CACHE_GET_ROOT        = 'Permissions:Rbac:Roles:getRoot';
    const CACHE_GET_PERMISSIONS = 'Permissions:Rbac:Roles:getPermissions';
    const CACHE_GET_SIBLINGS    = 'Permissions:Rbac:Roles:getSiblings:';

    /**
     * Tablename
     * 
     * @var string
     */
    public $tableName = 'rbac_roles';

    /**
     * User roles Tablename
     * 
     * @var string
     */
    public $userRolesTableName = 'rbac_user_roles';

    /**
     * Column name user primary key
     * 
     * @var string
     */
    public $columnUserPrimaryKey = 'user_id';

    /**
     * Column name role primary key
     * 
     * @var string
     */
    public $columnUserRolePrimaryKey = 'role_id';

    /**
     * Column name primary key
     * 
     * @var string
     */
    public $primaryKey = 'role_id';

    /**
     * Column name parent id
     * 
     * @var string
     */
    public $parentId = 'parent_id';

    /**
     * Column name text
     * 
     * @var string
     */
    public $text = 'role_name';

    /**
     * Column name role type
     * 
     * @var string
     */
    public $type = 'role_type';

    /**
     * Column name lft
     * 
     * @var string
     */
    public $lft = 'lft';

    /**
     * Column name rgt
     * 
     * @var string
     */
    public $rgt = 'rgt';

    /**
     * TreeDb object
     * 
     * @var object
     */
    public $treeDb;

    /**
     * Cache object
     * 
     * @var object
     */
    public $cache;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->c      = $c;
        $this->treeDb = new Db($this->c, ['connection' => 'rbac']);
        $this->cache  = $c['cache'];
        $this->c['config']->load('rbac');  // load rbac constants

        $this->c['model.roles'] = function () {
             return new ModelRoles($this->c);
        };

        // RBAC "roles" table variable definitions
        $this->tableName                = RBAC_ROLES_DB_TABLENAME;
        $this->primaryKey               = RBAC_ROLES_COLUMN_PRIMARY_KEY;
        $this->parentId                 = RBAC_ROLES_COLUMN_PARENT_ID;
        $this->text                     = RBAC_ROLES_COLUMN_TEXT;
        $this->type                     = RBAC_ROLES_COLUMN_TYPE;
        $this->lft                      = RBAC_ROLES_COLUMN_LEFT;
        $this->rgt                      = RBAC_ROLES_COLUMN_RIGHT;
        
        // RBAC "user_roles" table variable definitions
        $this->userRolesTableName       = RBAC_USER_ROLES_DB_TABLENAME;
        $this->columnUserPrimaryKey     = RBAC_USER_ROLES_TABLE_USER_PRIMARY_KEY;
        $this->columnUserRolePrimaryKey = RBAC_USER_ROLES_TABLE_ROLE_PRIMARY_KEY;
        
        // RBAC "permissions" table variable definitions
        $this->permtableName            = RBAC_PERM_DB_TABLENAME;
        $this->permPrimaryKey           = RBAC_PERM_COLUMN_PRIMARY_KEY;
        $this->permText                 = RBAC_PERM_COLUMN_TEXT;
        
        // RBAC "role_permissions" table variable definitions
        $this->rolePermTableName        = RBAC_ROLE_PERM_DB_TABLENAME;
        $this->rolePermRolePrimaryKey   = RBAC_ROLE_PERM_TABLE_ROLES_PRIMARY_KEY;
        $this->rolePermPrimaryKey       = RBAC_ROLE_PERM_TABLE_PERM_PRIMARY_KEY;
        
        // RBAC "op_permissions" table variable definitions
        $this->opPermsTableName         = RBAC_OP_PERM_DB_TABLENAME;
        $this->opPermsRolePrimaryKey    = RBAC_OP_PERM_TABLE_ROLE_PRIMARY_KEY;
        
        $this->treeDb->setTablename($this->tableName);
        $this->treeDb->setPrimaryKey($this->primaryKey);
        $this->treeDb->setParentId($this->parentId);
        $this->treeDb->setText($this->text);
        $this->treeDb->setLeft($this->lft);
        $this->treeDb->setRight($this->rgt);
    }
    
    /**
     * Add root
     * 
     * @param string $roleName role name
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function addRoot($roleName, $extra = array())
    {
        $this->deleteCache();
        $this->treeDb->addTree((string)$roleName, (array)$extra);
    }

    /**
     * Add node
     * 
     * @param int   $roleId   role id
     * @param int   $roleName role name
     * @param array $extra    extra data
     * 
     * @return void
     */
    public function add($roleId, $roleName, $extra = array())
    {
        $this->deleteCache();
        $this->treeDb->addChild((int)$roleId, (string)$roleName, (array)$extra);
    }

    /**
     * Append node
     * 
     * @param int   $roleId   role id
     * @param int   $roleName role name
     * @param array $extra    extra data
     * 
     * @return void
     */
    public function append($roleId, $roleName, $extra = array())
    {
        $this->deleteCache();
        $this->treeDb->appendChild((int)$roleId, (string)$roleName, (array)$extra);
    }

    /**
     * Move as first node
     * 
     * @param int $sourceId source role id
     * @param int $targetId target role id
     * 
     * @return void
     */
    public function moveAsFirst($sourceId, $targetId)
    {
        $this->deleteCache();
        $this->treeDb->moveAsFirstChild((int)$sourceId, (string)$targetId);
    }

    /**
     * Move as last node
     * 
     * @param int $sourceId source role id
     * @param int $targetId target role id
     * 
     * @return void
     */
    public function moveAsLast($sourceId, $targetId)
    {
        $this->deleteCache();
        $this->treeDb->moveAsLastChild((int)$sourceId, (string)$targetId);
    }

    /**
     * Move as next sibling
     * 
     * @param int $sourceId source role id
     * @param int $targetId target role id
     * 
     * @return void
     */
    public function moveAsNextSibling($sourceId, $targetId)
    {
        $this->deleteCache();
        $this->treeDb->moveAsNextSibling((int)$sourceId, (string)$targetId);
    }

    /**
     * Move as prev sibling
     * 
     * @param int $sourceId source role id
     * @param int $targetId target role id
     * 
     * @return void
     */
    public function moveAsPrevSibling($sourceId, $targetId)
    {
        $this->deleteCache();
        $this->treeDb->moveAsPrevSibling((int)$sourceId, (string)$targetId);
    }

    /**
     * Get all roles
     * 
     * @param string $select     select column
     * @param int    $expiration expiration time
     * 
     * @return array
     */
    public function getAllRoles($select = null, $expiration = 7200)
    {
        $key         = static::CACHE_GET_ROLES;
        $select      = ($select == null) ? $this->primaryKey . ',' . $this->text : $select;
        $resultArray = $this->cache->get($key);
        if ($resultArray == false) {  // If not exist in the cache
            $queryResultArray = $this->treeDb->getAllTree((string)$select); // do sql query
            $resultArray      = ($queryResultArray == false) ? 'empty' : $queryResultArray;
            $this->cache->set($key, $resultArray, $expiration);
        }
        if ($resultArray == 'empty') {
            return null;
        }
        return $resultArray;
    }

    /**
     * Get root
     * 
     * @param string $select     select column
     * @param int    $expiration expiration time
     * 
     * @return array
     */
    public function getRoot($select = null, $expiration = 7200)
    {
        $key         = static::CACHE_GET_ROOT;
        $select      = ($select == null) ? $this->primaryKey . ',' . $this->text : $select;
        $resultArray = $this->cache->get($key);

        if ($resultArray == false) { // If not exist in the cache
            $queryResultArray = $this->treeDb->getRoot((string)$select); // do sql query
            $resultArray      = ($queryResultArray == false) ? 'empty' : $queryResultArray;
            $this->cache->set($key, $resultArray, $expiration);
        }
        if ($resultArray == 'empty') {
            return null;
        }
        return $resultArray;
    }

    /**
     * Get siblings
     * 
     * @param int    $roleId     role id
     * @param string $select     select column
     * @param string $expiration cache
     * 
     * @return array
     */
    public function getSiblings($roleId, $select = null, $expiration = 7200)
    {
        $key         = static::CACHE_GET_SIBLINGS . $roleId;
        $select      = ($select == null) ? $this->primaryKey . ',' . $this->text : $select;
        $resultArray = $this->cache->get($key);
        if ($resultArray == false) {  // If not exist in the cache
            $queryResultArray = $this->treeDb->getSiblings((int)$roleId, (string)$select); // do sql query
            $resultArray = ($queryResultArray == false) ? 'empty' : $queryResultArray;
            $this->cache->set($key, $resultArray, $expiration);
        }
        if ($resultArray == 'empty') {
            return null;
        }
        return $resultArray;
    }

    /**
     * Get users
     * 
     * @param int    $roleId     role id
     * @param string $select     select column
     * @param string $expiration cache
     * 
     * @return array
     */
    public function getUsers($roleId, $select = null, $expiration = 7200)
    {
        $key         = static::CACHE_GET_USERS . $roleId;
        $select      = ($select == null) ? $this->primaryKey . ',' . $this->text : $select;
        $resultArray = $this->cache->get($key);
        if ($resultArray == false) {  // If not exist in the cache
            $queryResultArray = $this->c['model.roles']->getUsersSqlQuery((int)$roleId, (string)$select); // do sql query
            $resultArray = ($queryResultArray == false) ? 'empty' : $queryResultArray;
            $this->cache->set($key, $resultArray, $expiration);
        }
        if ($resultArray == 'empty') {
            return null;
        }
        return $resultArray;
    }

    /**
     * Get permissions
     * 
     * @param int    $roleId     role id
     * @param string $select     select column
     * @param string $expiration cache
     * 
     * @return array
     */
    public function getPermissions($roleId, $select = null, $expiration = 7200)
    {
        $key         = static::CACHE_GET_PERMISSIONS . $roleId;
        $select      = ($select == null) ? $this->primaryKey . ',' . $this->text : $select;
        // $resultArray = $this->cache->get($key);
        $resultArray = false;
        
        if ($resultArray == false) {  // If not exist in the cache
            $queryResultArray = $this->c['model.roles']->getPermissionsSqlQuery((int)$roleId, (string)$select); // do sql query
            $resultArray = ($queryResultArray == false) ? 'empty' : $queryResultArray;
            $this->cache->set($key, $resultArray, $expiration);
        }
        if ($resultArray == 'empty') {
            return null;
        }
        return $resultArray;
    }

    /**
     * Update node
     * 
     * @param int   $roleId role id
     * @param array $data   data
     * 
     * @return boolean
     */
    public function update($roleId, $data = array())
    {
        $this->deleteCache();
        return $this->treeDb->updateNode($roleId, $data);
    }

    /**
     * Delete Role Perm Assign
     * 
     * @param int $roleId role primary key
     * 
     * @return object statement of Pdo
     */
    public function deletePermissions($roleId)
    {
        $roleId = Utils::doubleArrayConvert($roleId, $this->primaryKey);
        $roleId = array_reverse($roleId);

        $this->deleteCache();
        
        return $this->c['model.roles']->deletePermissions($roleId);
    }

    /**
     * Delete Assign User Roles
     * 
     * @param int $roleId role primary key
     * 
     * @return object statement of Pdo
     */
    public function deleteUsers($roleId)
    {
        $roleId = Utils::doubleArrayConvert($roleId, $this->primaryKey);
        $roleId = array_reverse($roleId);

        $this->deleteCache();
        
        return $this->c['model.roles']->deleteUsers($roleId);
    }

    /**
     * Delete Assign Operations
     * 
     * @param int $roleId role primary key
     * 
     * @return object statement of Pdo
     */
    public function deleteOperations($roleId)
    {
        $roleId = Utils::doubleArrayConvert($roleId, $this->primaryKey);
        $roleId = array_reverse($roleId);

        $this->deleteCache();
        
        return $this->c['model.roles']->deleteOperationsByRoleId($roleId);
    }

    /**
     * Delete the role
     * 
     * @param int $roleId role id
     * 
     * @return boolean
     */
    public function delete($roleId)
    {
        $this->deleteCache();
        $this->deleteOperations((int)$roleId);
        $this->deleteUsers((int)$roleId);
        $this->deletePermissions((int)$roleId);
        $this->treeDb->deleteNode((int)$roleId);
    }

    /**
     * Delete cache
     * 
     * @return void
     */
    public function deleteCache()
    {
        $this->cache->delete(User::CACHE_GET_ROLES);
        $this->cache->delete(User::CACHE_HAS_PAGE_PERMISSION);
        $this->cache->delete(User::CACHE_HAS_OBJECT_PERMISSION);
        $this->cache->delete(User::CACHE_HAS_ELEMENT_PERMISSION);
    }

    /**
     * Get PDO Statement Object
     * 
     * @return array
     */
    public function getStatement()
    {
        return $this->c['model.roles']->getStatement();
    }
}

// END Roles.php File
/* End of file Roles.php

/* Location: .Obullo/Permissions/Rbac/Roles.php */
