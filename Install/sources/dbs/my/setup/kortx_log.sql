DROP TABLE IF EXISTS {prefix}_log CASCADE;
CREATE TABLE  {prefix}_log (
    log_id integer NOT NULL AUTO_INCREMENT,
    entity_id bigint,
    entity_type character(3),
    subject_trace character varying(39),
    subject_id bigint,
    action text CHARACTER SET utf8,
    source_type character(6),
    priority character varying(8),
    old_value text CHARACTER SET utf8,
    new_value text CHARACTER SET utf8,
    date timestamp DEFAULT NOW(),
    PRIMARY KEY (log_id)
);