DROP TABLE IF EXISTS "{prefix}_strings";
CREATE TABLE "{prefix}_strings" (
    "string_id" INTEGER,
    "lang_id" INT,
    "value" TEXT COLLATE NOCASE,
    "ent_id" INTEGER,
    "ent_type" TEXT
) STRICT;

CREATE INDEX "{prefix}_strings_ent_id" ON {prefix}_strings(ent_id);

CREATE INDEX "{prefix}_strings_lang_id" ON {prefix}_strings(lang_id);

CREATE INDEX "{prefix}_strings_string_id" ON {prefix}_strings(string_id);

