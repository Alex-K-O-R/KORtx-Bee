DROP TABLE IF EXISTS {prefix}_strings;
CREATE TABLE {prefix}_strings (
    string_id bigint,
    lang_id smallint,
    value character varying(21835) CHARACTER SET utf8,
    ent_id bigint,
    ent_type character(3)
);

CREATE INDEX {prefix}_strings_entity_id ON {prefix}_strings (ent_id) USING BTREE;

CREATE INDEX {prefix}_strings_lang_id ON {prefix}_strings (lang_id) USING BTREE;

CREATE INDEX {prefix}_strings_string_id ON {prefix}_strings (string_id) USING BTREE;