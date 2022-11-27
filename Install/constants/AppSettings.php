<?php
//no constants in traits till php 8+
namespace app;

interface ICoreSettings {
    const SESSION_USER_INFO_BLOCK = 'user';
    const SESSION_PORTAL_INFO_BLOCK = 'site';
    const SESSION_GLOBAL_FILTER_INFO_BLOCK = 'filter';

    const AUTH_NO_ACCESS = 1;
    const AUTH_NO_ACTIVATION = 2;
    const AUTH_BLOCKED = 3;

    const searchIsRequiredCodeWord = 'searchBy';

    const DEFAULT_LANGUAGE_ACRONYM = 'RU';
    const PROFILER_MODE = 0;
    const DEBUG_MODE = 0;

    const LOG_DIRECTORY = '/illusions_of_eternity';
}
?>