<?php
namespace app\dba\constants;
use SQLite3;
use app\dba\FileDBA;
use app\dba\inners\DBConnProvider;
class DBSettings extends DBConnProvider {
    const host = '{adr}';
    const dbprfx = '{prefix}';
    const dbnm = self::dbprfx.'_series';
    const pass = '{pass}';

    public function open($useDefaultDb){
        $dbpath = FileDBA::DIR_PATH(self::host, "/").self::dbnm;
        if(!is_readable($dbpath)){
            $Dir = new FileDBA(self::host);
            $this->connection = new SQLite3($Dir->getFileLink(self::dbnm), SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, self::pass);
        } else {
            $this->connection = new SQLite3($dbpath, SQLITE3_OPEN_READWRITE, self::pass);
        }

        if($this->connection->lastErrorCode()!=0) $this->connection = false;
    }

    protected function close()
    {
        if($this->connection) $this->connection->close();
    }
}