CREATE TABLE IF NOT EXISTS users(
                id int unsigned not null primary key auto_increment,
                tg_user_id int unsigned not null unique,
                tg_chat_id int unsigned not null unique,
                first_name varchar(255),
                last_name varchar(255),
                username varchar(255),
                current_chat_status int unsigned not null
            );