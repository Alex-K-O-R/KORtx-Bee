DROP TABLE IF EXISTS {prefix}_favorites;
CREATE TABLE {prefix}_favorites (
    ent_type character(3),
    ent_id bigint,
    fav_type character(3),
    fav_id bigint,
    inverse boolean DEFAULT false NOT NULL
);