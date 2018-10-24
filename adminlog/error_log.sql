drop table if exists "log";

CREATE TABLE "error_log" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "user_id" integer(11),
  "level" integer(4),
  "request_uri" text,
  "category" text(200),
  "ip" text(20),
  "title" text,
  "message" text,
  "get" text,
  "post" text,
  "files" real,
  "cookie" text,
  "session" text,
  "server" text,
  "create_at" text
);

create index "idx_log_level" on "log" ("level");
create index "idx_log_category" on "log" ("category");
create index "idx_log_user_id" on "log" ("user_id");