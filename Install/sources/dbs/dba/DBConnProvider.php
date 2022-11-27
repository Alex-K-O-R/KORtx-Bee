<?php
namespace app\dba\inners;

abstract class DBConnProvider {
    protected $connection;
    public final function __construct($useDefaultDb = true){
        $this->open($useDefaultDb);
        if(!$this->connection){
            print 'Unable to connect to the database with provided credentials. Application stopped.';
            exit;
        }
    }

    public final function __destruct(){
        $this->close();
        if (isset($this->connection)) unset($this->connection);
    }

    protected abstract function open($useDefaultDb);
    protected abstract function close();
    public final function get(){
        return $this->connection;
    }
}