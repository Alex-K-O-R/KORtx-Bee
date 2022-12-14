DROP TABLE IF EXISTS {prefix}_languages;
CREATE TABLE {prefix}_languages (
    lang_id INT NOT NULL,
    acronym TEXT,
    fullname TEXT,
    php_datemask TEXT DEFAULT 'Y.m.d' NOT NULL,
    js_datemask TEXT DEFAULT 'YYYY-MM-DD' NOT NULL
) STRICT;
INSERT INTO "{prefix}_languages" ("lang_id", "acronym", "fullname", "php_datemask", "js_datemask") VALUES
(0,	'RU',	'Русский',	'd.m.Y',	'DD.MM.YYYY'),
(1,	'EN',	'English',	'm.d.Y',	'MM.DD.YYYY');
