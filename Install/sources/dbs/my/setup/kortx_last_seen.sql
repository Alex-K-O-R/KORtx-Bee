DROP TABLE IF EXISTS {prefix}_last_seen;
CREATE TABLE {prefix}_last_seen (
    view_date timestamp DEFAULT now() NOT NULL,
    user_id bigint,
    subject_type character(3),
    subject_id bigint
);
