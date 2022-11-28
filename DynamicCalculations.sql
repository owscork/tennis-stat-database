USE tennisDB;

DROP FUNCTION IF EXISTS aceCount;

DELIMITER //

CREATE FUNCTION aceCount(name VARCHAR(40), start DATE, finish DATE) RETURNS INTEGER READS SQL DATA
BEGIN
	DECLARE ace_count INT;
	
	SET ace_count = (SELECT (SUM(num_ace)/COUNT(*)) FROM Match_Stats M_S INNER JOIN Player P INNER JOIN Tennis_Match M WHERE ((name = P.winner_name) 
	AND (M_S.player_id = P.winner_id)) AND ((M.tourney_date between start and finish) AND (M_S.match_id = M.mach_id)));
	
	RETURN ace_count;	

END
//

DELIMITER ;

SELECT aceCount('Rafael Nadal', '2010-01-01', '2019-12-31');

DROP PROCEDURE IF EXISTS showAggreggateStatistics;
DELIMITER //
CREATE PROCEDURE showAggreggateStatistics(IN name VARCHAR(35), IN start DATE, IN finish DATE)
BEGIN
SELECT SUM(num_ace), SUM(num_df)
FROM Match_Stats M_S INNER JOIN Tennis_Match M INNER JOIN Player P
WHERE (name = P.winner_name AND P.winner_id = M_S.player_id) AND ((M.tourney_date between start and finish) AND (M.mach_id = M_S.match_id));
END
//

DELIMITER ;


CALL showAggreggateStatistics('Roger Federer', '2010-01-01', '2019-12-31');

CREATE VIEW topAces AS
	SELECT winner_name, SUM(M_S.num_ace) AS total_aces FROM Player P INNER JOIN Match_Stats M_S WHERE M_S.player_id = P.winner_id
	GROUP BY winner_name 
	ORDER BY total_aces DESC
	LIMIT 10;

SELECT * FROM topAces;

DROP TRIGGER IF EXISTS onInsertionPlayer;
DELIMITER //
CREATE TRIGGER onInsertionPlayer AFTER INSERT ON Player
FOR EACH ROW
BEGIN
UPDATE Player
SET winner_ioc = 'USR'
WHERE winner_ioc = 'RUS' OR winner_ioc = 'EST';

END
//

DELIMITER ;





