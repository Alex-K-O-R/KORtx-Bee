If you are experiencing troubles with mysqli due to encoding/character set missmatch
or server requested authentication method unknown to the client [caching_sha2_password],
you should:

1. Add to my.cnf (Linux) or my.ini (Windows) these lines:

    [client]
    default-character-set=utf8

    [mysql]
    default-character-set=utf8

    [mysqld]
    collation-server = utf8_unicode_ci
    character-set-server = utf8
    default_authentication_plugin = mysql_native_password

2. Restart MySQL;

3. Execute via console/manager sqls to recreate passwords:
    ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root_password';
    ALTER USER admin IDENTIFIED WITH mysql_native_password BY 'admin_password';
    etc...