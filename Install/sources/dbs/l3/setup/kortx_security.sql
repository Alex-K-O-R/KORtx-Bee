DROP TABLE IF EXISTS "{prefix}_security";
CREATE TABLE "{prefix}_security" (
    "sec_id" INTEGER PRIMARY KEY NOT NULL,
    "login" TEXT,
    "pass" TEXT,
    "activated" INT DEFAULT 0 NOT NULL,
    "add_date" TEXT DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "last_login_date" TEXT NULL DEFAULT NULL
) STRICT;
