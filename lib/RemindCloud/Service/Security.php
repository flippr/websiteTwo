<?php
/*
 * @formatter:off
 */
namespace RemindCloud\Service;

use Spiffy\Doctrine\Container;
use RemindCloud\Entity\User;
use Zend_Acl;
use Zend_Acl_Assert_Interface;
use Zend_Acl_Resource;
use Zend_Acl_Resource_Interface;
use Zend_Auth;
use Zend_Auth_Adapter_Interface;
use Zend_Auth_Result;
use Zend_Exception;

/*
 * @formatter:on
 */
class Security implements Zend_Auth_Adapter_Interface
{
    /**
     * Zend_Auth
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * Zend_Auth
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Doctrine service.
     * @var Spiffy\Doctrine\Container
     */
    protected $_doctrine;

    /**
     * Username.
     * @var string
     */
    protected $_username;

    /**
     * Password.
     * @var string
     */
    protected $_password;

    /**
     * Constructor.
     */
    public function __construct(Container $doctrine)
    {
        $this->_acl = new Zend_Acl();
        $this->_auth = Zend_Auth::getInstance();
        $this->_doctrine = $doctrine;

        $this->_initAcl();
    }

    /**
     * (non-PHPdoc)
     * @see Zend_Auth_Adapter_Interface::authenticate()
     */
    public function authenticate()
    {
        $user = $this->findUser();

        if ($user)
        {
            if ($this->checkPassword($user, $this->_password))
            {
                $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
            } else
            {
                $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null, array('The email or password you supplied was incorrect.'));
            }
        } else
        {
            $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, array('The email or password you supplied was incorrect.'));
        }

        return $result;
    }

    /**
     * Finds a user from the db.
     *
     * @return null|RemindCloud\Entity\User
     */
    public function findUser()
    {
        $repository = $this->_doctrine->getEntityManager()->getRepository('RemindCloud\Entity\User');
        return $repository->findByEmail($this->_username);
    }

    /**
     * Get the acl instance.
     *
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Set the user.
     */
    public function setUser($user)
    {
        $this->_auth->getStorage()->write($user);
        $this->_initAcl();
    }

    /**
     * Get the authenticated user.
     *
     * @return RemindCloud\Entity\User
     */
    public function getUser()
    {
        if ($this->isAuthenticated())
        {
            // todo: remove the need for this query
            if ($this->_auth->getIdentity()->id)
            {
                $entityManager = $this->_doctrine->getEntityManager();
                return $entityManager->getRepository('RemindCloud\Entity\User')->find($this->_auth->getIdentity()->id);
            }
            return $this->_auth->getIdentity();
        }
        return new User();
    }

    /**
     * Attempts to log a user in.
     *
     * @param string $username
     * @param string $password
     * @return Zend_Auth_Result
     */
    public function login($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;

        $result = $this->_auth->authenticate($this);

        if ($result->isValid())
        {
            $this->getUser()->updateLastLogin();
        }

        return $result;
    }

    /**
     * @return Zend_Auth_Result
     */
    public function hasRights()
    {
        $this->user = $this->findUser();
        $result = $this->user->isCompanyAdmin();
        return $result;
    }

    /**
     * Logs out the current user.
     */
    public function logout()
    {
        $this->_auth->clearIdentity();
    }

    /**
     * Proxy to Zend_Acl.allow
     *
     * @param  Zend_Acl_Resource_Interface|string|array $resources
     * @param  string|array $privileges
     * @param  Zend_Acl_Assert_Interface $assert
     * @uses   Zend_Acl::setRule()
     * @return Zend_Acl Provides a fluent interface
     */
    public function allow($resources = null, $privileges = null, Zend_Acl_Assert_Interface $assert = null)
    {
        $this->_verifyResource($resources);
        $this->getAcl()->allow($this->getUser(), $resources, $privileges, $assert);
    }

    /**
     * Proxy to Zend_Acl.deny
     *
     * @param  Zend_Acl_Resource_Interface|string|array $resources
     * @param  string|array $privileges
     * @param  Zend_Acl_Assert_Interface $assert
     * @uses   Zend_Acl::setRule()
     * @return Zend_Acl Provides a fluent interface
     */
    public function deny($resources = null, $privileges = null, Zend_Acl_Assert_Interface $assert = null)
    {
        $this->_verifyResource($resources);
        $this->getAcl()->deny($this->getUser(), $resources, $privileges, $assert);
    }

    /**
     * Proxy to Zend_Acl.isAllowed
     *
     * @param  Zend_Acl_Resource_Interface|string $resource
     * @param  string $privilege
     * @uses   Zend_Acl::get()
     * @uses   Zend_Acl_Role_Registry::get()
     * @return boolean
     */
    public function isAllowed($resource = null, $privilege = null)
    {
        $this->_verifyResource($resource);
        return $this->getAcl()->isAllowed($this->getUser(), $resource, $privilege);
    }

    /**
     * Checks a users authenticity.
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->_auth->hasIdentity();
    }

    /**
     * Checks if a password input matches the users password.
     *
     * @param RemindCloud\Entity\User $user
     * @param string $password
     * @return boolean
     */
    public function checkPassword(User $user, $password)
    {
        //return ($user->_password === $this->_generatePassword($password, $user->salt));
        return ($user->password === md5($password));
    }

    /**
     * Transforms a users password to a secure version.
     *
     * @param RemindCloud\Entity\User $user
     * @return void
     */
    public function transformPassword(User $user)
    {
        $user->setPassword($this->_generatePassword($user->password, $user->salt));
    }

    public function getResources()
    {
        return array();
    }

    public function getGroupResources($group)
    {
        return $group->resources ? $group->resources : array();
    }

    /**
     * Initialize ACL properties.
     */
    protected function _initAcl()
    {
        $this->getAcl()->removeAll();
        $this->getAcl()->removeRoleAll();
        $this->getAcl()->addRole($this->getUser());

        $resources = $this->getResources();
        foreach ($resources as $resource)
        {
            if (is_object($resource))
            {
                $resource = $resource->name;
            }

            $this->_verifyResource($resource);
        }

        if (!($group = $this->getUser()->group))
        {
            return;
        }

        if ($this->getUser()->isAdmin())
        {
            $this->getAcl()->allow();
            return;
        }

        // add group resources to user
        $resources = $this->getGroupResources($this->getUser()->group);
        foreach ($resources as $resource)
        {
            if (is_object($resource))
            {
                $resource = $resource->name;
            }
            $this->allow($resource, array('create', 'read', 'update', 'delete'));
        }
    }

    /**
     * Splits a resource by parent:child relations and verifies that all
     * parents exist prior to adding the child resource.
     *
     * @param Zend_Acl_Resource_Interface|array $resource
     */
    protected function _verifyResource($resources)
    {
        if (!is_array($resources))
        {
            $resources = array($resources);
        }

        $acl = $this->getAcl();
        foreach ($resources as $resource)
        {
            if ($resource instanceof Zend_Acl_Resource_Interface)
            {
                $resource = $resource->getResourceId();
            } elseif (!is_string($resource))
            {
                throw new Zend_Exception('Invalid resource type for _verifyResource');
            }

            if (!preg_match('/^(?:.+):(?<resources>.+$)/', $resource, $matches))
            {
                if (!$acl->has($resource))
                {
                    $acl->add(new Zend_Acl_Resource($resource));
                }
                return;
            }

            $resources = explode('.', $matches['resources']);
            foreach ($resources as $r)
            {
                $parent = trim(substr($resource, 0, strpos($resource, $r)), '.');
                if (substr($parent, strlen($parent) - 1) == ':')
                {
                    $name = "{$parent}{$r}";
                } else
                {
                    $name = "{$parent}.{$r}";
                }

                if (!$acl->has($parent))
                {
                    $acl->add(new Zend_Acl_Resource($parent));
                }
                if (!$acl->has($name))
                {
                    $acl->add(new Zend_Acl_Resource($name), $parent);
                }
            }
        }
    }

    /**
     * Performs password generation on a string.
     *
     * @param string $password
     * @param string $salt
     */
    protected function _generatePassword($password, $salt)
    {
        $password = $password . $salt;
        foreach (range(0, 100) as $iteration)
        {
            $password = hash('sha1', $password);
        }
        return $password;
    }

}
