DROP TABLE IF EXISTS "{prefix}_favorites";
CREATE TABLE {prefix}_favorites (
    ent_type TEXT,
    ent_id INTEGER,
    fav_type TEXT,
    fav_id INTEGER,
    inverse INT DEFAULT 0 NOT NULL
) STRICT;

