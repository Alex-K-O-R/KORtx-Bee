<?php
namespace app\dba\constants;


class DBChanges {
    // Способы изменений
    const auto = 'auto';
    const manual = 'manual';

    // Коды изменений
    const fio = 'change_user_fio';
    const login = 'change_login';
    const pass = 'change_password';
//    const auto = 'auto';

    // Приоритеты изменений
    const level_low = 'low';
    const level_medium = 'medium';
    const level_high = 'high';
    const level_critical = 'critical';
}
