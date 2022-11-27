DROP TABLE IF EXISTS "{prefix}_favorites";
CREATE TABLE {prefix}_favorites (
    ent_type character(3),
    ent_id integer,
    fav_type character(3),
    fav_id integer,
    inverse boolean DEFAULT false NOT NULL
);
