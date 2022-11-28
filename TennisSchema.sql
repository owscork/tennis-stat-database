DROP DATABASE IF EXISTS tennisDB;

CREATE DATABASE tennisDB;

USE tennisDB;

CREATE TABLE Player (winner_id INT NOT NULL AUTO_INCREMENT, winner_name VARCHAR(50), winner_ioc VARCHAR(10), winner_hand VARCHAR(1), CHECK(winner_hand in ('L','R','U')), PRIMARY KEY (winner_id));

CREATE TABLE Tournaments (tourney_id VARCHAR(40) NOT NULL, tourney_name VARCHAR(50), tourney_date DATE, tourney_year VARCHAR(4), draw_size INT, surface VARCHAR(15), tourney_level VARCHAR(2), PRIMARY KEY (tourney_id));

CREATE TABLE Tennis_Match (mach_id INT NOT NULL AUTO_INCREMENT, tourney_date DATE, score VARCHAR(60), tourney_id VARCHAR(40), round VARCHAR(6), minutes VARCHAR(5), PRIMARY KEY (mach_id), FOREIGN KEY (tourney_id) REFERENCES Tournaments(tourney_id) ON DELETE CASCADE);

CREATE TABLE Match_Stats (match_id INT, player_id INT, player_age INT, player_ht VARCHAR(4), num_ace INT, num_df INT, outcome VARCHAR(1), FOREIGN KEY (match_id) REFERENCES Tennis_Match(mach_id) ON DELETE CASCADE, FOREIGN KEY (player_id) REFERENCES Player(winner_id) ON DELETE SET NULL);


