<?php

 // CREATE TABLE `dream11_points` (
    //     `id` INT AUTO_INCREMENT PRIMARY KEY,
    //     `action` VARCHAR(100) NOT NULL,
    //     `role_id` TINYINT NOT NULL,  -- 1: Batsman, 2: Bowler, 3: All-Rounder, 4: Wicket-Keeper
    //     `points` FLOAT NOT NULL,
    //     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    // );
    


    // INSERT INTO dream11_points (action, role_id, points) VALUES
    // ('Run Scored', 1, 1),
    // ('Run Scored', 2, 1),
    // ('Run Scored', 3, 1),
    // ('Run Scored', 4, 1),
    
    // ('Boundary (4 runs)', 1, 1),
    // ('Six', 1, 2),
    
    // ('Dot Ball', 2, 0.1),
    // ('Wicket Taken', 2, 25),
    // ('Catch', 1, 8),
    // ('Catch', 2, 8),
    // ('Catch', 3, 8),
    // ('Catch', 4, 8),
    
    // ('Stumping', 2, 12), -- Bowler
    // ('Stumping', 4, 12), -- Wicket-Keeper
    
    // ('Run Out (Direct Hit)', 1, 12),
    // ('Run Out (Direct Hit)', 2, 12),
    // ('Run Out (Direct Hit)', 3, 12),
    // ('Run Out (Direct Hit)', 4, 12),
    
    // ('Run Out (Thrower)', 1, 6),
    // ('Run Out (Thrower)', 2, 6),
    // ('Run Out (Thrower)', 3, 6),
    // ('Run Out (Thrower)', 4, 6),
    
    // ('Run Out (Catcher)', 1, 6),
    // ('Run Out (Catcher)', 2, 6),
    // ('Run Out (Catcher)', 3, 6),
    // ('Run Out (Catcher)', 4, 6),
    
    // ('Maiden Over', 2, 12),
    
    // ('Economy Rate: Excellent', 2, 10),
    // ('Economy Rate: Good', 2, 7),
    // ('Economy Rate: Average', 2, 4),
    
    // ('Strike Rate: Excellent', 1, 12),
    // ('Strike Rate: Excellent', 3, 12),
    // ('Strike Rate: Excellent', 4, 12),
    
    // ('Strike Rate: Good', 1, 8),
    // ('Strike Rate: Average', 1, 4),
    
    // ('Duck (out without scoring)', 1, -5),
    // ('Duck (out without scoring)', 3, -5),
    // ('Duck (out without scoring)', 4, -5),
    
    // ('Captain Bonus', 1, 2),
    // ('Captain Bonus', 2, 2),
    // ('Captain Bonus', 3, 2),
    // ('Captain Bonus', 4, 2),
    
    // ('Vice-Captain Bonus', 1, 1.5),
    // ('Vice-Captain Bonus', 2, 1.5),
    // ('Vice-Captain Bonus', 3, 1.5),
    // ('Vice-Captain Bonus', 4, 1.5);
    

//     -- tournaments
// CREATE TABLE `tournaments` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `name` VARCHAR(100),
//   `short_name` VARCHAR(20),
//   `start_date` DATE,
//   `end_date` DATE,
//   `status` TINYINT DEFAULT 1
// );

// -- teams
// CREATE TABLE `teams` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `name` VARCHAR(100),
//   `short_name` VARCHAR(20),
//   `tournament_id` INT,
//   FOREIGN KEY (`tournament_id`) REFERENCES tournaments(`id`) ON DELETE CASCADE
// );

// -- players
// CREATE TABLE `players` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `name` VARCHAR(100),
//   `role` ENUM('Batsman','Bowler','All-Rounder','Wicket-Keeper'),
//   `team_id` INT,
//   FOREIGN KEY (`team_id`) REFERENCES teams(`id`) ON DELETE CASCADE
// );

// -- player_stats
// CREATE TABLE `player_stats` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `player_id` INT,
//   `matches` INT DEFAULT 0,
//   `runs` INT DEFAULT 0,
//   `wickets` INT DEFAULT 0,
//   `strike_rate` FLOAT DEFAULT 0,
//   `economy` FLOAT DEFAULT 0,
//   FOREIGN KEY (`player_id`) REFERENCES players(`id`) ON DELETE CASCADE
// );

// -- schedules
// CREATE TABLE `schedules` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `match_date` DATETIME,
//   `tournament_id` INT,
//   `team1_id` INT,
//   `team2_id` INT,
//   `venue` VARCHAR(100),
//   FOREIGN KEY (`tournament_id`) REFERENCES tournaments(`id`) ON DELETE CASCADE,
//   FOREIGN KEY (`team1_id`) REFERENCES teams(`id`) ON DELETE CASCADE,
//   FOREIGN KEY (`team2_id`) REFERENCES teams(`id`) ON DELETE CASCADE
// );

// CREATE TABLE `venues` (
//     `id` INT AUTO_INCREMENT PRIMARY KEY,
//     `name` VARCHAR(100) NOT NULL,
//     `location` VARCHAR(150) DEFAULT NULL,
//     `capacity` INT DEFAULT NULL
//   );
  
//   -- Update schedules table to use venue_id instead of text
//   ALTER TABLE `schedules`
//     ADD COLUMN `venue_id` INT AFTER `team2_id`,
//     DROP COLUMN `venue`,
//     ADD FOREIGN KEY (`venue_id`) REFERENCES venues(`id`) ON DELETE SET NULL;

// CREATE TABLE `pitch_types` (
//     `id` INT AUTO_INCREMENT PRIMARY KEY,
//     `type_name` VARCHAR(100) NOT NULL, -- e.g., "Spin-friendly", "Pace-friendly", "Good for Batting"
//     `description` TEXT DEFAULT NULL
//   );

//   CREATE TABLE `venue_pitch_types` (
//     `id` INT AUTO_INCREMENT PRIMARY KEY,
//     `venue_id` INT NOT NULL,
//     `pitch_type_id` INT NOT NULL,
//     FOREIGN KEY (`venue_id`) REFERENCES venues(`id`) ON DELETE CASCADE,
//     FOREIGN KEY (`pitch_type_id`) REFERENCES pitch_types(`id`) ON DELETE CASCADE
//   );

// INSERT INTO pitch_types (type_name) VALUES
// ('Spin-friendly'),
// ('Pace-friendly'),
// ('Good for Batting'),
// ('Difficult for Batting'),
// ('High Bounce'),
// ('Low Bounce'),
// ('Good for Chasing'),
// ('Favors Swing');

// ALTER TABLE `players`
// ADD COLUMN `special_ability` VARCHAR(100) AFTER `role`;


// -- Add credit points and role_id as integer mapping in players (if not yet added)
// ALTER TABLE players 
//     ADD COLUMN credit_points FLOAT DEFAULT 0,
//     ADD COLUMN role_id INT GENERATED ALWAYS AS (
//       CASE role
//         WHEN 'Batsman' THEN 1
//         WHEN 'Bowler' THEN 2
//         WHEN 'All-Rounder' THEN 3
//         WHEN 'Wicket-Keeper' THEN 4
//         ELSE 0
//       END
//     ) STORED;

// -- Add indexes for faster filters
// CREATE INDEX idx_players_role_id ON players(role_id);
// CREATE INDEX idx_players_credit_points ON players(credit_points);

// -- Add special_ability if not already present
// ALTER TABLE players ADD COLUMN special_ability VARCHAR(100);

// -- Example of player stats filtering columns already present (matches, runs, wickets, strike_rate, economy)
// -- You can create indexes if needed
// CREATE INDEX idx_player_stats_runs ON player_stats(runs);
// CREATE INDEX idx_player_stats_strike_rate ON player_stats(strike_rate);

// -- Example: pitch_types and venue_pitch_types tables are already created for pitch type filtering by venue

// -- If you want to support filtering players by tournament (via team -> tournament)
// -- You might want a view or a join table for easier queries:
// CREATE VIEW player_tournament AS
// SELECT p.id AS player_id, t.id AS tournament_id
// FROM players p
// JOIN teams t ON p.team_id = t.id
// JOIN tournaments tr ON t.tournament_id = tr.id;

//ALTER TABLE players ADD COLUMN fitness_status ENUM('Fit', 'Injured', 'Doubtful') DEFAULT 'Fit';





  
