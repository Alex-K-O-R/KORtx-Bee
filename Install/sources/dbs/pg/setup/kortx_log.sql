
DROP SEQUENCE IF EXISTS {prefix}_log_log_id_seq CASCADE;
CREATE SEQUENCE {prefix}_log_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

DROP TABLE IF EXISTS "{prefix}_log";
CREATE TABLE {prefix}_log (
    log_id integer DEFAULT nextval('{prefix}_log_log_id_seq'::regclass) NOT NULL,
    entity_id integer,
    entity_type character(3),
    subject_trace character varying,
    subject_id integer,
    action character varying,
    source_type character varying,
    priority character varying,
    old_value character varying,
    new_value character varying,
    date timestamp without time zone DEFAULT now() NOT NULL
);
