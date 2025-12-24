<?php
// Direct SQLite insert for AI predictions

$dbPath = __DIR__ . '/database/database.sqlite';

if (!file_exists($dbPath)) {
    die("❌ Database file not found at: $dbPath\n");
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to SQLite database!\n\n";
    
    // Get all group stage matches
    $stmt = $pdo->query("
        SELECT m.id, m.home_team_id, m.away_team_id,
               ht.name as home_name, ht.fifa_ranking as home_ranking, ht.world_cup_titles as home_titles,
               at.name as away_name, at.fifa_ranking as away_ranking, at.world_cup_titles as away_titles
        FROM matches m
        JOIN teams ht ON m.home_team_id = ht.id
        JOIN teams at ON m.away_team_id = at.id
        WHERE m.status = 'scheduled' 
        AND m.stage = 'group'
        AND ht.is_placeholder = 0
        AND at.is_placeholder = 0
        LIMIT 48
    ");
    
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($matches) . " group matches\n\n";
    
    $created = 0;
    foreach ($matches as $match) {
        // Simple AI logic
        $homeRanking = $match['home_ranking'] ?? 50;
        $awayRanking = $match['away_ranking'] ?? 50;
        $homeTitles = $match['home_titles'] ?? 0;
        $awayTitles = $match['away_titles'] ?? 0;
        
        $rankingDiff = $awayRanking - $homeRanking;
        $titlesDiff = $homeTitles - $awayTitles;
        
        // Determine scores based on strength
        if ($rankingDiff > 20 || $titlesDiff > 2) {
            // Home favorite
            $homeScore = rand(2, 3);
            $awayScore = rand(0, 1);
            $confidence = rand(70, 80);
        } elseif ($rankingDiff < -20 || $titlesDiff < -2) {
            // Away favorite
            $homeScore = rand(0, 1);
            $awayScore = rand(2, 3);
            $confidence = rand(70, 80);
        } else {
            // Close match
            $homeScore = rand(1, 2);
            $awayScore = rand(1, 2);
            $confidence = rand(50, 65);
        }
        
        $reasoning = json_encode([
            'fifa_ranking_diff' => $rankingDiff,
            'wc_titles_diff' => $titlesDiff,
            'home_advantage' => true
        ]);
        
        $now = date('Y-m-d H:i:s');
        
        // Insert or replace
        $insertStmt = $pdo->prepare("
            INSERT OR REPLACE INTO ai_predictions 
            (game_id, predicted_home_score, predicted_away_score, confidence_percentage, reasoning, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $insertStmt->execute([
            $match['id'],
            $homeScore,
            $awayScore,
            $confidence,
            $reasoning,
            $now,
            $now
        ]);
        
        $homeName = json_decode($match['home_name'], true)['en'] ?? 'Unknown';
        $awayName = json_decode($match['away_name'], true)['en'] ?? 'Unknown';
        
        echo "✅ $homeName vs $awayName → $homeScore-$awayScore ($confidence%)\n";
        $created++;
    }
    
    echo "\n🎉 Successfully created $created AI predictions!\n";
    
    // Verify
    $count = $pdo->query("SELECT COUNT(*) FROM ai_predictions")->fetchColumn();
    echo "📊 Total AI predictions in database: $count\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
