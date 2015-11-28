<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/28/2015
 * Time: 10:59 AM
 */

class DbConfig {
    const CONFIG_DB_TYPE = "sqlite";

    const CONFIG_DB_LOCATION = "chat.db";
    const CONFIG_DB_HOST = "";
    const CONFIG_DB_USER = "";
    const CONFIG_DB_PASS = "";

    static public function getDbTablesSetup(){
        $tables = array();

        $tables[] =
            "CREATE TABLE users (".
                "id INTEGER PRIMARY KEY,".
                "username TEXT NOT NULL UNIQUE,".
                "last_activity INTEGER NOT NULL".
            ")";

        $tables[] = "CREATE UNIQUE INDEX username_idx ON users (username)";

        $tables[] = "INSERT INTO users (username, last_activity) VALUES ('public', 999999999999)";

        $tables[] =
            "CREATE TABLE chats (".
                "id INTEGER PRIMARY KEY,".
                "last_activity INTEGER NOT NULL".
            ")";

        $tables[] = "INSERT INTO chats (last_activity) VALUES (999999999999)";

        $tables[] =
            "CREATE TABLE messages (".
                "id INTEGER PRIMARY KEY,".
                "user_id INTEGER NOT NULL,".
                "chat_id INTEGER NOT NULL,".
                "message TEXT NOT NULL,".
                "insert_time INTEGER NOT NULL".
            ")";

        $tables[] = "CREATE INDEX username_insert_time_idx ON messages (user_id, insert_time)";

        $tables[] =
            "CREATE TABLE user_chat_rel (".
                "id INTEGER PRIMARY KEY,".
                "chat_id INTEGER NOT NULL,".
                "user_id INTEGER NOT NULL,".
                "last_activity INTEGER NOT NULL".
            ")";

        $tables[] = "CREATE UNIQUE INDEX user_chat_idx ON user_chat_rel (user_id, chat_id)";

        return $tables;
    }
}