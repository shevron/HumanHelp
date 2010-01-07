DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
    id           INTEGER NOT NULL PRIMARY KEY,
    author_name  VARCHAR(50) NOT NULL,
    author_email VARCHAR(50) NOT NULL,
    created_at   INTEGER NOT NULL,
    book         VARCHAR(100) NOT NULL,
    page         VARCHAR(200) NOT NULL,
    comment      TEXT NOT NULL,
    flags        INTEGER DEFAULT 0,
    token        VARCHAR(40)
);
