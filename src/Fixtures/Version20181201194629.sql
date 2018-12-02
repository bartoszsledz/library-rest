CREATE TABLE users
(
  id        INT UNSIGNED AUTO_INCREMENT NOT NULL,
  roles     JSON                               NOT NULL,
  username  VARCHAR(64)                        NOT NULL,
  email     VARCHAR(64)                        NOT NULL,
  password  VARCHAR(255)                       NOT NULL,
  token     VARCHAR(255)                       NOT NULL,
  public_id BIGINT UNSIGNED NOT NULL,
  created   DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  modified  DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  UNIQUE INDEX username (username),
  UNIQUE INDEX email (email),
  UNIQUE INDEX token (token),
  UNIQUE INDEX public_id (public_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE borrow
(
  id          INT UNSIGNED AUTO_INCREMENT NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  book_id     INT UNSIGNED NOT NULL,
  date_borrow DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  public_id   BIGINT UNSIGNED NOT NULL,
  created     DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  modified    DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  UNIQUE INDEX public_id (public_id),
  INDEX       user_id (user_id),
  INDEX       book_id (book_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE session
(
  id        INT UNSIGNED AUTO_INCREMENT NOT NULL,
  user_id   INT UNSIGNED NOT NULL,
  token     VARCHAR(255)                       NOT NULL,
  expires   DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  public_id BIGINT UNSIGNED NOT NULL,
  created   DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  modified  DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  UNIQUE INDEX token (token),
  UNIQUE INDEX public_id (public_id),
  INDEX     user_id (user_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE history
(
  id          INT UNSIGNED AUTO_INCREMENT NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  book_id     INT UNSIGNED NOT NULL,
  date_borrow DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  date_return DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  public_id   BIGINT UNSIGNED NOT NULL,
  created     DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  modified    DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  UNIQUE INDEX public_id (public_id),
  INDEX       user_id (user_id),
  INDEX       book_id (book_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE book
(
  id          INT UNSIGNED AUTO_INCREMENT NOT NULL,
  ean8        INT UNSIGNED NOT NULL,
  title       VARCHAR(64)                        NOT NULL,
  author      VARCHAR(64)                        NOT NULL,
  description LONGTEXT                           NOT NULL,
  available   TINYINT(1) NOT NULL,
  public_id   BIGINT UNSIGNED NOT NULL,
  created     DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  modified    DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  UNIQUE INDEX title (title),
  UNIQUE INDEX author (author),
  UNIQUE INDEX public_id (public_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci ENGINE = InnoDB;
ALTER TABLE borrow
  ADD CONSTRAINT FK_55DBA8B0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE borrow
  ADD CONSTRAINT FK_55DBA8B016A2B381 FOREIGN KEY (book_id) REFERENCES users (id);
ALTER TABLE session
  ADD CONSTRAINT FK_D044D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE history
  ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE history
  ADD CONSTRAINT FK_27BA704B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id);