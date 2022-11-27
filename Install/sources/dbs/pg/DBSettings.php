<?php
namespace app\dba\constants;
use app\dba\inners\DBConnProvider;
class DBSettings extends DBConnProvider {
    const host = '{adr}';
    const dbprfx = '{prefix}';
    const dbnm = self::dbprfx.'_series';
    const user = '{usr}';
    const pass = '{pass}';

    protected function open($useDefaultDb)
    {
        $this->connection = pg_connect('host='.self::host.' port=5432 dbname='.(($useDefaultDb)?self::dbnm:'postgres').' user='.self::user.' password='.self::pass);
    }

    protected function close()
    {
        if($this->connection && (is_object($this->connection)!==false)) pg_close($this->connection);
    }
}