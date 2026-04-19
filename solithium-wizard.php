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
define( 'SLWIZ_DEMO_MODE', false ); // true = catalogue fictif; false = produits WooCommerce réels

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
    slwiz_register_account_endpoint();
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'slwiz_deactivate' );
function slwiz_deactivate() {
    flush_rewrite_rules();
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
        'currentUserName'  => is_user_logged_in() ? wp_get_current_user()->display_name : '',
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

/* ───────────────────────────────────────────────
   COMPTE WOOCOMMERCE — SECTION DEVIS
─────────────────────────────────────────────── */
add_action( 'init', 'slwiz_register_account_endpoint' );
function slwiz_register_account_endpoint() {
    add_rewrite_endpoint( 'slwiz-devis', EP_ROOT | EP_PAGES );
}

add_filter( 'woocommerce_account_menu_items', 'slwiz_add_quotes_menu_item' );
function slwiz_add_quotes_menu_item( $items ) {
    $logout = $items['customer-logout'] ?? null;
    unset( $items['customer-logout'] );

    $items['slwiz-devis'] = slwiz_get_current_lang() === 'fr' ? __( 'Devis', 'solithium-wizard' ) : __( 'Quotes', 'solithium-wizard' );

    if ( null !== $logout ) {
        $items['customer-logout'] = $logout;
    }

    return $items;
}

add_action( 'woocommerce_account_slwiz-devis_endpoint', 'slwiz_render_account_quotes' );
function slwiz_render_account_quotes() {
    $is_fr = slwiz_get_current_lang() === 'fr';

    if ( ! is_user_logged_in() ) {
        echo $is_fr ? '<p>Vous devez être connecté.</p>' : '<p>You must be logged in.</p>';
        return;
    }

    $quotes = get_user_meta( get_current_user_id(), 'slwiz_quotes', true );
    if ( ! is_array( $quotes ) || empty( $quotes ) ) {
        echo $is_fr ? '<p>Aucun devis enregistré pour le moment.</p>' : '<p>No saved quotes yet.</p>';
        return;
    }

    echo $is_fr ? '<h3>Mes derniers devis (max 10)</h3>' : '<h3>My latest quotes (max 10)</h3>';
    echo '<table class="shop_table shop_table_responsive my_account_orders"><thead><tr>';
    echo $is_fr
        ? '<th>Date</th><th>Nom du client</th><th>Total</th><th>Produits</th><th>Services</th><th>Commande</th>'
        : '<th>Date</th><th>Client Name</th><th>Total</th><th>Items</th><th>Services</th><th>Order</th>';
    echo '</tr></thead><tbody>';

    foreach ( $quotes as $quote ) {
        $date = esc_html( $quote['created_at'] ?? '—' );
        $total = esc_html( ( $quote['currency'] ?? '$' ) . number_format( (float) ( $quote['grand_total'] ?? 0 ), 2 ) );
        $client_name = esc_html( $quote['client_name'] ?? '—' );
        $items_list = '';
        if ( is_array( $quote['items'] ?? null ) ) {
            foreach ( $quote['items'] as $item ) {
                $n = esc_html( (string) ( $item['name'] ?? '' ) );
                $q = (int) ( $item['qty'] ?? 1 );
                $items_list .= '<div>' . $n . ( $q > 1 ? ' × ' . $q : '' ) . '</div>';
            }
        }
        if ( '' === $items_list ) $items_list = '—';

        $services = is_array( $quote['services'] ?? null ) ? $quote['services'] : [];
        $installer_raw = (string) ( $services['installer'] ?? '' );
        $delivery_raw  = (string) ( $services['delivery'] ?? '' );
        $callback_raw  = ! empty( $services['callback'] );

        $installer_val = match ( $installer_raw ) {
            'yes' => $is_fr ? 'Oui' : 'Yes',
            'no'  => $is_fr ? 'Non' : 'No',
            default => '—',
        };

        $delivery_val = match ( $delivery_raw ) {
            'delivery' => $is_fr ? 'Livraison à domicile' : 'Home delivery',
            'pickup'   => $is_fr ? 'Cueillette en magasin' : 'In-store pickup',
            default    => '—',
        };

        $callback_val = $callback_raw
            ? ( $is_fr ? 'Oui' : 'Yes' )
            : ( $is_fr ? 'Non' : 'No' );

        $services_text = ( $is_fr ? 'Installateur: ' : 'Installer: ' ) . $installer_val
            . ' | ' . ( $is_fr ? 'Livraison: ' : 'Delivery: ' ) . $delivery_val
            . ' | ' . ( $is_fr ? 'Rappel: ' : 'Callback: ' ) . $callback_val;

        $order_id = (int) ( $quote['order_id'] ?? 0 );
        $order_link = $order_id
            ? '<a href="' . esc_url( wc_get_endpoint_url( 'view-order', $order_id, wc_get_page_permalink( 'myaccount' ) ) ) . '">#' . $order_id . '</a>'
            : '—';

        echo '<tr>';
        echo '<td>' . $date . '</td>';
        echo '<td>' . $client_name . '</td>';
        echo '<td>' . $total . '</td>';
        echo '<td>' . $items_list . '</td>';
        echo '<td>' . $services_text . '</td>';
        echo '<td>' . $order_link . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
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
