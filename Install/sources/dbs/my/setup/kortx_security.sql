DROP TABLE IF EXISTS {prefix}_security CASCADE;
CREATE TABLE {prefix}_security (
    sec_id bigint AUTO_INCREMENT NOT NULL,
    login character varying(48),
    pass character varying(32),
    activated boolean DEFAULT false NOT NULL,
    add_date timestamp DEFAULT NOW() NOT NULL,
    last_login_date timestamp NULL DEFAULT NULL,
    PRIMARY KEY (sec_id)
);
