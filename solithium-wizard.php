<?php
/**
 * Plugin Name: Solithium Solar Wizard
 * Plugin URI:  https://solithium.ca
 * Description: Assistant de configuration solaire — calcul des besoins, sélection des composantes, ajout au panier WooCommerce. | Solar configuration wizard — needs assessment, component selection, WooCommerce cart integration.
 * Version:     1.0.0
 * Author:      Solithium 2
 * Author URI:  https://solithium.ca
 * Text Domain: solithium-wizard
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * WC requires at least: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SLWIZ_VERSION',  '1.1.0' );
define( 'SLWIZ_DIR',      plugin_dir_path( __FILE__ ) );
define( 'SLWIZ_URL',      plugin_dir_url( __FILE__ ) );
define( 'SLWIZ_DEMO_MODE', true ); // true = catalogue fictif; false = produits WooCommerce réels

/* ───────────────────────────────────────────────
   CHARGEMENT DES CLASSES / LOAD CLASSES
─────────────────────────────────────────────── */
require_once SLWIZ_DIR . 'includes/class-wizard-calculator.php';
require_once SLWIZ_DIR . 'includes/class-wizard-products.php';
require_once SLWIZ_DIR . 'includes/class-wizard-ajax.php';
require_once SLWIZ_DIR . 'includes/class-wizard-admin.php';

/* ───────────────────────────────────────────────
   ACTIVATION — Création de la table de sessions
─────────────────────────────────────────────── */
register_activation_hook( __FILE__, 'slwiz_activate' );
function slwiz_activate() {
    global $wpdb;
    $table   = $wpdb->prefix . 'slwiz_sessions';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        session_key VARCHAR(64)     NOT NULL,
        user_id     BIGINT UNSIGNED DEFAULT NULL,
        data        LONGTEXT        NOT NULL,
        created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_key (session_key)
    ) {$charset};";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

/* ───────────────────────────────────────────────
   SHORTCODE  [solithium_wizard]
─────────────────────────────────────────────── */
add_shortcode( 'solithium_wizard', 'slwiz_render' );
function slwiz_render( $atts ) {
    ob_start();
    include SLWIZ_DIR . 'templates/wizard-template.php';
    return ob_get_clean();
}

/* ───────────────────────────────────────────────
   ENQUEUE ASSETS
─────────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', 'slwiz_enqueue' );
function slwiz_enqueue() {

    // Wizard JS — chargé AVANT Alpine pour que les composantes soient définies
    wp_enqueue_script(
        'slwiz-wizard',
        SLWIZ_URL . 'assets/js/wizard.js',
        [],
        SLWIZ_VERSION,
        true
    );


// Alpine.js (CDN — pas de build process requis)
    wp_enqueue_script(
        'alpinejs',
        'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
        [],
        '3.14.1',
        true   // defer
    );


    // Paramètres PHP → JS
    wp_localize_script( 'slwiz-wizard', 'slwizParams', [
        'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
        'nonce'            => wp_create_nonce( 'slwiz_nonce' ),
        'isLoggedIn'       => is_user_logged_in() ? 1 : 0,
        'currentLang'      => slwiz_get_current_lang(),
        'loginUrl'         => wp_login_url( get_permalink() ),
        'demoMode'         => SLWIZ_DEMO_MODE ? 1 : 0,
        'currency'         => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$',
        'siteUrl'          => get_site_url(),
        'notifEmail'       => get_option( 'slwiz_notification_email', get_option( 'admin_email' ) ),
        'contactPhone'     => get_option( 'slwiz_contact_phone', '' ),
        'accessories'      => Solithium_Admin::get_accessories(),
    ] );

    // CSS
    wp_enqueue_style(
        'slwiz-style',
        SLWIZ_URL . 'assets/css/wizard.css',
        [],
        SLWIZ_VERSION
    );
}

/**
 * Détermine la langue de la page courante.
 * Priorité: Polylang > locale WP > fallback FR.
 *
 * @return string "fr" ou "en"
 */
function slwiz_get_current_lang() {
    $lang = '';

    if ( function_exists( 'pll_current_language' ) ) {
        $lang = (string) pll_current_language( 'slug' );
    }

    if ( '' === $lang ) {
        $lang = (string) determine_locale();
    }

    $lang = strtolower( $lang );

    if ( str_starts_with( $lang, 'en' ) ) {
        return 'en';
    }

    return 'fr';
}
