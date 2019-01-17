<?php


namespace Deployee\Components\Persistence;

/**
 * @mixin \PDO
 */
class PDO
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $options;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct(string $dsn, string $username = '', string $password = '', array $options = [])
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->pdo(), $name], $arguments);
    }

    /**
     * @return \PDO
     */
    private function pdo(): \PDO
    {
        if($this->pdo === null){
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        }

        return $this->pdo;
    }
}