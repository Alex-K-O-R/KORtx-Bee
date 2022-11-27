DROP TABLE IF EXISTS "{prefix}_languages";
CREATE TABLE "public"."{prefix}_languages" (
    "lang_id" smallint NOT NULL,
    "acronym" character varying,
    "fullname" character varying,
    "php_datemask" character varying DEFAULT 'Y.m.d' NOT NULL,
    "js_datemask" character varying DEFAULT 'YYYY-MM-DD' NOT NULL
) WITH (oids = false);

INSERT INTO "{prefix}_languages" ("lang_id", "acronym", "fullname", "php_datemask", "js_datemask") VALUES
(0,	'RU',	'Русский',	'd.m.Y',	'DD.MM.YYYY'),
(1,	'EN',	'English',	'm.d.Y',	'MM.DD.YYYY');
