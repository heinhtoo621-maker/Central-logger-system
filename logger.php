<?php

/**
 * Class CentralLogger
 * Implements the Singleton Design Pattern to ensure a unified logging context 
 * across the entire web application ecosystem.
 */
class CentralLogger {
    private static ?CentralLogger $instance = null;
    private array $logHistory = [];

    // Private constructor prevents external instantiation via 'new'
    private function __construct() {}

    // Global access point to retrieve the single active instance
    public static function getInstance(): CentralLogger {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Records system actions with varying severity levels
    public function log(string $level, string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}]: {$message}";
        $this->logHistory[] = $logEntry;
        
        // Simulating writing directly into a secure core system log file
        echo "{$logEntry}\n";
    }

    public function getHistory(): array {
        return $this->logHistory;
    }
}

/**
 * Class ExceptionWatchdog
 * Monitors and intercepts unhandled runtime disruptions and anomalies.
 */
class ExceptionWatchdog {
    private CentralLogger $logger;

    public function __construct() {
        $this->logger = CentralLogger::getInstance();
    }

    // Triggers when a dangerous system runtime error or violation occurs
    public function catchException(string $errorType, string $errorMessage): void {
        $this->logger->log("CRITICAL", "Watchdog intercepted exception -> Type: {$errorType} | Message: {$errorMessage}");
    }
}

// ==========================================
// Ecosystem Simulation & Testing
// ==========================================

echo "--- 🛡️ Centralized Logger & Exception Watchdog ---\n\n";

// Accessing the unified logging entity from different components
$authLogger = CentralLogger::getInstance();
$paymentLogger = CentralLogger::getInstance();

// 1. Simulate routine, informational operational logs
$authLogger->log("INFO", "User 'admin_kyaw' successfully established a secure session.");
$paymentLogger->log("INFO", "Invoice #9481 generated. Processing payment pipeline...");
echo "------------------------------------------------------------------------\n";

// 2. Initialize the Exception Watchdog to monitor the ecosystem
$watchdog = new ExceptionWatchdog();

// Simulate a critical runtime failure (e.g., Database connection dropped)
$watchdog->catchException("DatabaseConnectionException", "Could not connect to host 'db.server.local' (Timeout).");
echo "------------------------------------------------------------------------\n";

// 3. Output the central tracking history to prove singleton unification
echo "\n[System Status Verification] Total Log entries compiled: " . count($authLogger->getHistory()) . "\n";