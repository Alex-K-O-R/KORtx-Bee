DROP TABLE IF EXISTS "{prefix}_log";
CREATE TABLE {prefix}_log (
    log_id INTEGER PRIMARY KEY NOT NULL,
    entity_id INTEGER,
    entity_type TEXT,
    subject_trace TEXT,
    subject_id INTEGER,
    action TEXT,
    source_type TEXT,
    priority TEXT,
    old_value TEXT,
    new_value TEXT,
    date TEXT DEFAULT CURRENT_TIMESTAMP NOT NULL
);
