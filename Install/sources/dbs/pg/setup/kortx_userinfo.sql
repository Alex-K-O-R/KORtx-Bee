
DROP SEQUENCE IF EXISTS {prefix}_user_info_user_id_seq CASCADE;
CREATE SEQUENCE {prefix}_user_info_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

DROP TABLE IF EXISTS "{prefix}_e_user_info";
CREATE TABLE {prefix}_e_user_info (
    user_id integer DEFAULT nextval('{prefix}_user_info_user_id_seq'::regclass) NOT NULL,
    sec_id integer,
    is_blocked boolean DEFAULT false NOT NULL,
    d_general_info bigint,
    d_additional_info bigint,
    selected_lang character varying,
    d_name bigint,
    d_surname bigint,
    d_job bigint,
    resource_hash character varying,
    avatar_path character varying,
    is_admin boolean DEFAULT false NOT NULL
);