DROP SEQUENCE IF EXISTS {prefix}_security_sec_id_seq CASCADE;
CREATE SEQUENCE {prefix}_security_sec_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
    
DROP TABLE IF EXISTS "{prefix}_security";
CREATE TABLE "public"."{prefix}_security" (
    "sec_id" integer DEFAULT nextval('{prefix}_security_sec_id_seq') NOT NULL,
    "login" character varying,
    "pass" character varying,
    "activated" boolean DEFAULT false NOT NULL,
    "add_date" timestamp without time zone DEFAULT now() NOT NULL,
    "last_login_date" timestamp without time zone NULL DEFAULT NULL
) WITH (oids = false);
