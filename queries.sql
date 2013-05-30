
-- win-tie-loss count for players for a specific game

SELECT players.*
	, COUNT(win_matches.id) AS wins
	, COUNT(lose_matches.id) AS losses
	, COUNT(tie_matches.id) AS ties
FROM players
	LEFT JOIN players_teams
		ON players_teams.player_id = players.id
	LEFT JOIN teams
		ON teams.id = players_teams.team_id
	LEFT JOIN matches AS win_matches
		ON (
			win_matches.id = teams.match_id
			AND win_matches.winning_team_id = teams.id
			AND win_matches.game_id = 1
		)
	LEFT JOIN matches AS lose_matches
		ON (
			lose_matches.id = teams.match_id
			AND lose_matches.winning_team_id <> teams.id
			AND lose_matches.winning_team_id <> 0
			AND lose_matches.game_id = 1
		)
	LEFT JOIN matches AS tie_matches
		ON (
			tie_matches.id = teams.match_id
			AND tie_matches.winning_team_id = 0
			AND tie_matches.game_id = 1
		)
GROUP BY players.id
ORDER BY players.name ASC ;



-- player's favorite games

SELECT players.*
	, players.id AS player_id
	, games.*
	, games.id AS game_id
	, COUNT(matches.id) AS played
FROM players
	LEFT JOIN players_teams
		ON players_teams.player_id = players.id
	LEFT JOIN teams
		ON teams.id = players_teams.team_id
	LEFT JOIN matches
		ON matches.id = teams.match_id
	LEFT JOIN games
		ON games.id = matches.game_id
WHERE players.id = 1
	AND matches.winning_team_id IS NOT NULL
GROUP BY games.id
ORDER BY players.name ASC,
	played DESC ;