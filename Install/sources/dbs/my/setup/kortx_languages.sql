DROP TABLE IF EXISTS {prefix}_languages;

CREATE TABLE {prefix}_languages (
    lang_id smallint NOT NULL,
    acronym varchar(2) CHARACTER SET utf8,
    fullname varchar(24) CHARACTER SET utf8,
    php_datemask varchar(18) NOT NULL DEFAULT 'Y.m.d',
    js_datemask varchar(32) NOT NULL DEFAULT 'YYYY-MM-DD'
);

INSERT INTO {prefix}_languages (lang_id, acronym, fullname, php_datemask, js_datemask) VALUES
(0,	'RU',	'Русский',	'd.m.Y',	'DD.MM.YYYY'),
(1,	'EN',	'English',	'm.d.Y',	'MM.DD.YYYY');