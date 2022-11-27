DROP TABLE IF EXISTS "{prefix}_strings";
CREATE TABLE "public"."{prefix}_strings" (
    "string_id" bigint,
    "lang_id" smallint,
    "value" text,
    "ent_id" integer,
    "ent_type" character(3)
) WITH (oids = false);

CREATE INDEX "{prefix}_strings_entity_id" ON "public"."{prefix}_strings" USING btree ("ent_id");

CREATE INDEX "{prefix}_strings_lang_id" ON "public"."{prefix}_strings" USING btree ("lang_id");

CREATE INDEX "{prefix}_strings_string_id" ON "public"."{prefix}_strings" USING btree ("string_id");
