<?php
namespace app\dba\constants;
use app\dba\inners\DBConnProvider;
class DBSettings extends DBConnProvider {
    const host = '{adr}';
    const dbprfx = '{prefix}';
    const dbnm = self::dbprfx.'_series';
    const user = '{usr}';
    const pass = '{pass}';

    public function open($useDefaultDb){
        $this->connection = mysqli_init();
        $this->connection->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
        $success = $this->connection->real_connect(self::host, self::user, self::pass, ($useDefaultDb)?self::dbnm:'', 3306, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
        if(!$success) $this->connection = false;
    }

    protected function close()
    {
        if($this->connection) $this->connection->close();
    }
}