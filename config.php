<?php
/**
 * Environment
 */
define("APP_ENVIRONMENT", "development");

/**
 * Show pdo error mode - bool
 * turn off in production
 */
if (APP_ENVIRONMENT === "development") {
  define("SHOW_PDO_ERROR", true);

} else {
  define("SHOW_PDO_ERROR", false);
}