<?php
/**
 * IP Handler - Core PHP
 * Handles IP detection, validation, and release operations
 */

require_once __DIR__ . '/db_helper.php';

function getIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function validateIp($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

function getVotedIps($pid) {
    $sql = "SELECT DISTINCT ip_address, voted_at FROM votes WHERE poll_id = ? AND is_released = 0";
    return fetchAll($sql, [$pid]);
}

function releaseIp($pid, $ip) {
    $db = getDb();
    
    // Get current vote info
    $stmt = $db->prepare("SELECT option_id, voted_at FROM votes WHERE poll_id = ? AND ip_address = ? AND is_released = 0");
    $stmt->execute([$pid, $ip]);
    $vote = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vote) return false;
    
    // Mark as released
    $stmt = $db->prepare("UPDATE votes SET is_released = 1, released_at = NOW() WHERE poll_id = ? AND ip_address = ? AND is_released = 0");
    $res = $stmt->execute([$pid, $ip]);
    
    // Record in history
    if ($res) {
        $stmt2 = $db->prepare("INSERT INTO vote_history (poll_id, option_id, ip_address, action, voted_at, released_at, created_at) VALUES (?, ?, ?, 'released', ?, NOW(), NOW())");
        $stmt2->execute([$pid, $vote['option_id'], $ip, $vote['voted_at']]);
    }
    
    return $res;
}

function getVoteHistory($pid, $ip) {
    $sql = "SELECT vh.*, po.option_text 
            FROM vote_history vh 
            JOIN poll_options po ON vh.option_id = po.id 
            WHERE vh.poll_id = ? AND vh.ip_address = ? 
            ORDER BY vh.created_at ASC";
    return fetchAll($sql, [$pid, $ip]);
}
