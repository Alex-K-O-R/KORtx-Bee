DROP TABLE IF EXISTS "{prefix}_last_seen";
CREATE TABLE {prefix}_last_seen (
    view_date timestamp without time zone DEFAULT now() NOT NULL,
    user_id integer,
    subject_type character(3),
    subject_id integer
);
