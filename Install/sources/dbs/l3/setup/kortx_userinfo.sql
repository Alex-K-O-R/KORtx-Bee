DROP TABLE IF EXISTS "{prefix}_e_user_info";
CREATE TABLE {prefix}_e_user_info (
    user_id INTEGER PRIMARY KEY NOT NULL,
    sec_id INTEGER,
    is_blocked INT DEFAULT 0 NOT NULL,
    d_general_info INTEGER,
    d_additional_info INTEGER,
    selected_lang TEXT,
    d_name INTEGER,
    d_surname INTEGER,
    d_job INTEGER,
    resource_hash TEXT,
    avatar_path TEXT,
    is_admin boolean INT DEFAULT 0 NOT NULL
);