<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class CreateAdminUserDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $locale;

    /**
     * CreateAdminUserDefinition constructor.
     * @param string $email
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $locale
     */
    public function __construct($email, $username, $password, $name, $locale = "en_GB")
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->locale = $locale;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection(get_object_vars($this));
    }
}