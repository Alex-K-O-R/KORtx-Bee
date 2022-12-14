DROP TABLE IF EXISTS "{prefix}_last_seen";
CREATE TABLE {prefix}_last_seen (
    view_date TEXT DEFAULT CURRENT_TIMESTAMP NOT NULL,
    user_id INTEGER,
    subject_type TEXT,
    subject_id INTEGER
);
