DROP TABLE IF EXISTS {prefix}_e_user_info;
CREATE TABLE {prefix}_e_user_info (
    user_id bigint AUTO_INCREMENT NOT NULL,
    sec_id bigint,
    is_blocked boolean DEFAULT false NOT NULL,
    d_general_info bigint,
    d_additional_info bigint,
    selected_lang character varying(2),
    d_name bigint,
    d_surname bigint,
    d_job bigint,
    resource_hash character varying(32),
    avatar_path text,
    is_admin boolean DEFAULT false NOT NULL,
    PRIMARY KEY (user_id)
);