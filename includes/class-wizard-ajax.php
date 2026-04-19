<?php
/**
 * Solithium_Ajax
 * Handlers AJAX — calcul, inscription, ajout panier
 * AJAX handlers — calculation, registration, add-to-cart
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Solithium_Ajax {

    public static function init(): void {
        // Actions disponibles pour tous (logged-in et non-connectés)
        $actions = [ 'calculate', 'register', 'login_check', 'add_to_cart', 'save_session', 'send_quote' ];
        foreach ( $actions as $action ) {
            add_action( 'wp_ajax_slwiz_'        . $action, [ __CLASS__, $action ] );
            add_action( 'wp_ajax_nopriv_slwiz_' . $action, [ __CLASS__, $action ] );
        }
    }

    /* ───────────────────────────────────────────────
       VÉRIFICATION DU NONCE
    ─────────────────────────────────────────────── */
    private static function verify(): void {
        if ( ! check_ajax_referer( 'slwiz_nonce', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => 'Requête non autorisée / Unauthorized request.' ], 403 );
        }
    }

    private static function get_post( string $key, $default = null ) {
        return isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : $default;
    }

    private static function get_post_raw( string $key, $default = null ) {
        // Pour les données JSON complexes
        return isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : $default;  // phpcs:ignore
    }

    /* ───────────────────────────────────────────────
       CALCULATE — Calcul des besoins + recommandations
       Retourne :
         - teaser  : données visibles sans inscription
         - needs   : (après inscription seulement)
         - products: (après inscription seulement)
    ─────────────────────────────────────────────── */
    public static function calculate(): void {
        self::verify();

        $scenario    = self::get_post( 'scenario', 'new' );
        $raw_data    = self::get_post_raw( 'wizard_data', '{}' );
        $wizard_data = json_decode( $raw_data, true );

        if ( ! is_array( $wizard_data ) ) {
            wp_send_json_error( [ 'message' => 'Données invalides / Invalid data.' ], 400 );
        }

        if ( $scenario === 'existing' ) {
            $needs = Solithium_Calculator::calculate_upgrade( $wizard_data );
        } else {
            $needs = Solithium_Calculator::calculate_new( $wizard_data );
        }

        // Données teaser — affichées AVANT inscription (pas de produits, pas de prix)
        $teaser = [
            'daily_kwh'         => $needs['daily_kwh']         ?? null,
            'monthly_kwh'       => $needs['monthly_kwh']       ?? null,
            'panel_capacity_w'  => $needs['panel_capacity_w']  ?? null,
            'batt_capacity_kwh' => $needs['batt_capacity_kwh'] ?? null,
            'system_voltage'    => $needs['system_voltage']    ?? null,
            'autonomy_days'     => $needs['autonomy_days']     ?? null,
            'area_sufficient'   => $needs['area_sufficient']   ?? null,
            'panel_area_m2'     => $needs['panel_area_m2']     ?? null,
        ];

        // Sauvegarder en transient pour la session courante
        $session_key = self::get_or_create_session();
        set_transient( 'slwiz_' . $session_key, [
            'scenario' => $scenario,
            'data'     => $wizard_data,
            'needs'    => $needs,
        ], 2 * HOUR_IN_SECONDS );

        // Si l'utilisateur est déjà connecté → on renvoie tout de suite les produits
        if ( is_user_logged_in() ) {
            $products = Solithium_Products::get_recommendations( $needs );
            wp_send_json_success([
                'authenticated' => true,
                'teaser'        => $teaser,
                'needs'         => $needs,
                'products'      => $products,
                'session_key'   => $session_key,
            ]);
        }

        wp_send_json_success([
            'authenticated' => false,
            'teaser'        => $teaser,
            'session_key'   => $session_key,
        ]);
    }

    /* ───────────────────────────────────────────────
       REGISTER — Inscription + retour des produits
    ─────────────────────────────────────────────── */
    public static function register(): void {
        self::verify();

        $email       = sanitize_email( self::get_post( 'email', '' ) );
        $first_name  = self::get_post( 'first_name', '' );
        $last_name   = self::get_post( 'last_name', '' );
        $password    = self::get_post_raw( 'password', '' );
        $session_key = self::get_post( 'session_key', '' );
        $lang        = self::get_post( 'lang', 'fr' );

        // Validation de base
        if ( ! is_email( $email ) ) {
            wp_send_json_error( [ 'message' => $lang === 'fr'
                ? 'Adresse courriel invalide.'
                : 'Invalid email address.' ]);
        }
        if ( strlen( $password ) < 8 ) {
            wp_send_json_error( [ 'message' => $lang === 'fr'
                ? 'Le mot de passe doit contenir au moins 8 caractères.'
                : 'Password must be at least 8 characters.' ]);
        }

        // Si déjà inscrit → connexion automatique
        $user = get_user_by( 'email', $email );
        if ( $user ) {
            // Tenter de connecter avec le mot de passe fourni
            $auth = wp_authenticate( $user->user_login, $password );
            if ( is_wp_error( $auth ) ) {
                wp_send_json_error( [ 'message' => $lang === 'fr'
                    ? 'Un compte existe déjà avec ce courriel. Vérifiez votre mot de passe.'
                    : 'An account already exists with this email. Please check your password.' ]);
            }
            wp_set_current_user( $auth->ID );
            wp_set_auth_cookie( $auth->ID );
        } else {
            // Créer le compte WordPress / WooCommerce
            $username = sanitize_user( strtolower( $first_name . '_' . $last_name ) );
            $username = self::unique_username( $username );

            $user_id = wp_create_user( $username, $password, $email );
            if ( is_wp_error( $user_id ) ) {
                wp_send_json_error( [ 'message' => $lang === 'fr'
                    ? 'Erreur lors de la création du compte : ' . $user_id->get_error_message()
                    : 'Account creation error: ' . $user_id->get_error_message() ]);
            }

            // Données supplémentaires
            wp_update_user([
                'ID'         => $user_id,
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'role'       => 'customer',
            ]);

            // Courriel de bienvenue WordPress standard
            wp_new_user_notification( $user_id, null, 'user' );

            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id );
        }

        // Récupérer la session et les produits recommandés
        $session_data = get_transient( 'slwiz_' . $session_key );
        if ( ! $session_data ) {
            wp_send_json_error( [ 'message' => $lang === 'fr'
                ? 'Session expirée. Veuillez recommencer le wizard.'
                : 'Session expired. Please restart the wizard.' ]);
        }

        $needs    = $session_data['needs'];
        $products = Solithium_Products::get_recommendations( $needs );

        // Associer la session au nouvel utilisateur
        global $wpdb;
        $table = $wpdb->prefix . 'slwiz_sessions';
        $wpdb->replace( $table, [
            'session_key' => $session_key,
            'user_id'     => get_current_user_id(),
            'data'        => wp_json_encode( $session_data ),
        ]);

        wp_send_json_success([
            'authenticated' => true,
            'needs'         => $needs,
            'products'      => $products,
        ]);
    }

    /* ───────────────────────────────────────────────
       LOGIN_CHECK — Vérification si déjà connecté au retour
    ─────────────────────────────────────────────── */
    public static function login_check(): void {
        self::verify();

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'authenticated' => false ] );
        }

        $session_key  = self::get_post( 'session_key', '' );
        $session_data = $session_key ? get_transient( 'slwiz_' . $session_key ) : null;

        if ( ! $session_data ) {
            wp_send_json_error( [ 'message' => 'Session expirée / Session expired.' ]);
        }

        $products = Solithium_Products::get_recommendations( $session_data['needs'] );

        wp_send_json_success([
            'authenticated' => true,
            'needs'         => $session_data['needs'],
            'products'      => $products,
        ]);
    }

    /* ───────────────────────────────────────────────
       ADD_TO_CART — Ajout des produits sélectionnés au panier WC
    ─────────────────────────────────────────────── */
    public static function add_to_cart(): void {
        self::verify();

        $raw_items = self::get_post_raw( 'items', '[]' );
        $items     = json_decode( $raw_items, true );
        $lang      = self::get_post( 'lang', 'fr' );

        if ( empty( $items ) || ! is_array( $items ) ) {
            wp_send_json_error( [ 'message' => 'Aucun produit sélectionné / No products selected.' ]);
        }

        if ( SLWIZ_DEMO_MODE ) {
            // Mode démo : simuler l'ajout au panier
            $total  = 0;
            $lines  = [];
            foreach ( $items as $item ) {
                $qty   = (int)($item['qty']   ?? 1);
                $price = (float)($item['price'] ?? 0);
                $total += $qty * $price;
                $lines[] = [
                    'name'  => sanitize_text_field( $item['name'] ?? '' ),
                    'qty'   => $qty,
                    'price' => $price,
                    'line'  => $qty * $price,
                ];
            }
            wp_send_json_success([
                'demo'     => true,
                'lines'    => $lines,
                'total'    => round( $total, 2 ),
                'message'  => $lang === 'fr'
                    ? 'Mode démo — en production, ces produits seraient ajoutés à votre panier WooCommerce.'
                    : 'Demo mode — in production, these products would be added to your WooCommerce cart.',
                'cart_url' => wc_get_cart_url(),
            ]);
        }

        // Mode production : ajouter les vrais produits WooCommerce au panier
        if ( ! function_exists( 'WC' ) ) {
            wp_send_json_error( [ 'message' => 'WooCommerce non disponible / WooCommerce not available.' ]);
        }

        WC()->cart->empty_cart();
        $added = [];
        $errors = [];

        foreach ( $items as $item ) {
            $resolved = self::resolve_wc_target_from_item( $item );
            $product_id = (int) ( $resolved['product_id'] ?? 0 );
            $variation_id = (int) ( $resolved['variation_id'] ?? 0 );
            $variation_attrs = (array) ( $resolved['variation_attrs'] ?? [] );

            $qty        = (int)($item['qty'] ?? 1);
            if ( ! $product_id ) continue;

            $result = WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation_attrs );
            if ( $result ) {
                $added[] = $product_id;
            } else {
                $errors[] = $item['name'] ?? "Product #{$product_id}";
            }
        }

        if ( empty( $added ) ) {
            wp_send_json_error([
                'message' => $lang === 'fr'
                    ? 'Impossible d\'ajouter les produits au panier.'
                    : 'Could not add products to cart.',
            ]);
        }

        wp_send_json_success([
            'cart_url'   => wc_get_cart_url(),
            'added_count' => count( $added ),
            'errors'     => $errors,
        ]);
    }

    /* ───────────────────────────────────────────────
       SEND_QUOTE — Envoi du courriel de demande à Solithium
       + confirmation au client
    ─────────────────────────────────────────────── */
    public static function send_quote(): void {
        self::verify();

        $session_key = self::get_post( 'session_key', '' );
        $lang        = self::get_post( 'lang', 'fr' );
        $services    = json_decode( self::get_post_raw( 'services',    '{}' ), true ) ?? [];
        $items       = json_decode( self::get_post_raw( 'items',       '[]' ), true ) ?? [];
        $accessories = json_decode( self::get_post_raw( 'accessories', '[]' ), true ) ?? [];
        $lines       = json_decode( self::get_post_raw( 'lines',       '[]' ), true ) ?? [];

        // Récupérer la session
        $session_data = $session_key ? get_transient( 'slwiz_' . $session_key ) : null;
        $needs        = $session_data['needs'] ?? [];

        // Infos client
        $user       = is_user_logged_in() ? wp_get_current_user() : null;
        $posted_client_name = trim( (string) self::get_post( 'client_name', '' ) );
        $client_name  = $posted_client_name !== '' ? $posted_client_name : '—';
        $client_email = $user ? $user->user_email : self::get_post( 'client_email', '' );

        // Normaliser les lignes pour éviter un total vide en cas de payload incomplet
        $all_items = array_merge( $items, $accessories );
        if ( empty( $all_items ) && is_array( $lines ) ) {
            foreach ( $lines as $line ) {
                $qty = (int) ( $line['qty'] ?? 1 );
                $total = (float) ( $line['total'] ?? 0 );
                $all_items[] = [
                    'name'  => sanitize_text_field( (string) ( $line['name'] ?? '' ) ),
                    'qty'   => $qty > 0 ? $qty : 1,
                    'price' => $qty > 0 ? $total / $qty : 0,
                ];
            }
        }

        // Calcul du total (priorité au total front s'il est valide)
        $grand_total = (float) self::get_post( 'grand_total', 0 );
        if ( $grand_total <= 0 ) {
            foreach ( $all_items as $it ) {
                $grand_total += (float)( $it['price'] ?? 0 ) * (int)( $it['qty'] ?? 1 );
            }
        }

        // Créer une commande WooCommerce (statut pending) si possible
        $order_id = 0;
        if ( ! SLWIZ_DEMO_MODE && function_exists( 'wc_create_order' ) && ! empty( $items ) ) {
            $order_id = self::create_wc_order_from_items( $items, $user ? (int) $user->ID : 0, $services, $client_email );
        }

        $currency = '$';

        if ( $user && ! empty( $user->ID ) ) {
            self::save_user_quote( (int) $user->ID, [
                'created_at'  => current_time( 'mysql' ),
                'grand_total' => $grand_total,
                'currency'    => $currency,
                'items'       => $all_items,
                'services'    => $services,
                'order_id'    => $order_id,
                'client_name' => $client_name,
            ] );
        }

        // Courriel de notification — destinataire Solithium
        $notif_to   = get_option( 'slwiz_notification_email', get_option( 'admin_email' ) );
        $site_name  = get_bloginfo( 'name' );

        $subject_solithium = $lang === 'fr'
            ? "[Solithium Wizard] Nouvelle demande de {$client_name}"
            : "[Solithium Wizard] New request from {$client_name}";

        $body_solithium = self::build_email_solithium( $lang, [
            'client_name'  => $client_name,
            'client_email' => $client_email,
            'needs'        => $needs,
            'items'        => $all_items,
            'accessories'  => [],
            'services'     => $services,
            'grand_total'  => $grand_total,
            'currency'     => $currency,
            'order_id'     => $order_id,
        ]);

        $headers_solithium = [
            'Content-Type: text/html; charset=UTF-8',
            "From: {$site_name} <{$notif_to}>",
        ];
        if ( is_email( $client_email ) ) {
            $headers_solithium[] = "Reply-To: {$client_email}";
        }

        wp_mail( $notif_to, $subject_solithium, $body_solithium, $headers_solithium );

        // Courriel de confirmation — client
        if ( $client_email ) {
            $subject_client = $lang === 'fr'
                ? 'Votre demande Solithium est bien reçue'
                : 'Your Solithium request has been received';

            $phone = get_option( 'slwiz_contact_phone', '' );
            $body_client = self::build_email_client( $lang, [
                'client_name'  => $client_name,
                'grand_total'  => $grand_total,
                'currency'     => $currency,
                'phone'        => $phone,
                'services'     => $services,
                'items'        => $all_items,
                'order_id'     => $order_id,
                'cart_url'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
                'account_url'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '',
                'shop_url'     => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '',
            ]);

            wp_mail( $client_email, $subject_client, $body_client, [
                'Content-Type: text/html; charset=UTF-8',
                "From: {$site_name} <{$notif_to}>",
            ]);
        }

        wp_send_json_success([
            'sent'    => true,
            'order_id' => $order_id,
            'message' => $lang === 'fr'
                ? 'Votre demande a été envoyée avec succès. Vous recevrez une confirmation par courriel.'
                : 'Your request has been sent successfully. You will receive a confirmation by email.',
        ]);
    }

    /* ───────────────────────────────────────────────
       FORMAT COURRIEL — Solithium (notification interne)
    ─────────────────────────────────────────────── */
    private static function build_email_solithium( string $lang, array $d ): string {
        $fr    = $lang === 'fr';
        $cur   = $d['currency'];
        $needs = $d['needs'];
        $svcs  = $d['services'];

        $installer_label = $svcs['installer'] === 'yes'
            ? ( $fr ? '✅ Oui' : '✅ Yes' )
            : ( $fr ? '❌ Non' : '❌ No' );

        $delivery_label = match( $svcs['delivery'] ?? '' ) {
            'delivery' => $fr ? '🚚 Livraison à domicile' : '🚚 Home delivery',
            'pickup'   => $fr ? '🏪 Cueillette en magasin' : '🏪 In-store pickup',
            default    => '—',
        };

        $callback_label = ! empty( $svcs['callback'] )
            ? ( $fr ? '📞 Rappel demandé : ' : '📞 Callback requested: ' ) . esc_html( $svcs['phone'] ?? '' )
            : ( $fr ? 'Pas de rappel' : 'No callback' );

        $items_html = '';
        foreach ( array_merge( $d['items'], $d['accessories'] ) as $it ) {
            $name  = esc_html( $it['name']  ?? '' );
            $qty   = (int)( $it['qty']   ?? 1 );
            $price = (float)( $it['price'] ?? 0 );
            $items_html .= "<tr><td style='padding:4px 8px'>{$name}</td><td style='padding:4px 8px;text-align:center'>{$qty}</td><td style='padding:4px 8px;text-align:right'>{$cur}" . number_format( $qty * $price, 2 ) . "</td></tr>";
        }

        $notes_label  = $fr ? 'Notes additionnelles' : 'Additional notes';
        $notes_val    = esc_html( $svcs['notes'] ?? '' );

        return "<!DOCTYPE html><html><body style='font-family:Arial,sans-serif;color:#222;max-width:650px;margin:auto'>
<div style='background:#1a2332;padding:20px 30px;'>
  <h1 style='color:#f5a623;margin:0;font-size:1.4rem'>☀ Solithium — " . ( $fr ? 'Nouvelle demande de devis' : 'New Quote Request' ) . "</h1>
</div>
<div style='padding:24px 30px;'>
  <table style='width:100%;border-collapse:collapse;margin-bottom:20px'>
    <tr><td style='padding:6px 0;width:40%;color:#666'>" . ( $fr ? 'Client' : 'Client' ) . "</td><td style='padding:6px 0'><strong>" . esc_html( $d['client_name'] ) . "</strong> &lt;" . esc_html( $d['client_email'] ) . "&gt;</td></tr>
    <tr><td style='padding:6px 0;color:#666'>" . ( $fr ? 'Consommation / jour' : 'Daily consumption' ) . "</td><td style='padding:6px 0'><strong>" . ( $needs['daily_kwh'] ?? '—' ) . " kWh</strong></td></tr>
    <tr><td style='padding:6px 0;color:#666'>" . ( $fr ? 'Tension système' : 'System voltage' ) . "</td><td style='padding:6px 0'><strong>" . ( $needs['system_voltage'] ?? '—' ) . " V</strong></td></tr>
    <tr><td style='padding:6px 0;color:#666'>" . ( $fr ? 'Autonomie' : 'Autonomy' ) . "</td><td style='padding:6px 0'><strong>" . ( $needs['autonomy_days'] ?? '—' ) . " " . ( $fr ? 'jours' : 'days' ) . "</strong></td></tr>
  </table>
  <h3 style='color:#2e8b57;margin-bottom:8px'>" . ( $fr ? 'Produits sélectionnés' : 'Selected products' ) . "</h3>
  <table style='width:100%;border-collapse:collapse;border:1px solid #ddd;margin-bottom:20px'>
    <thead><tr style='background:#2e8b57;color:#fff'>
      <th style='padding:6px 8px;text-align:left'>" . ( $fr ? 'Produit' : 'Product' ) . "</th>
      <th style='padding:6px 8px;text-align:center'>Qté</th>
      <th style='padding:6px 8px;text-align:right'>Prix</th>
    </tr></thead>
    <tbody>{$items_html}</tbody>
    <tfoot><tr style='background:#f5f5f5;font-weight:bold'>
      <td colspan='2' style='padding:6px 8px;text-align:right'>" . ( $fr ? 'Total estimé' : 'Estimated total' ) . "</td>
      <td style='padding:6px 8px;text-align:right'>{$cur}" . number_format( $d['grand_total'], 2 ) . "</td>
    </tr></tfoot>
  </table>
  <h3 style='color:#2e8b57;margin-bottom:8px'>" . ( $fr ? 'Services demandés' : 'Requested services' ) . "</h3>
  <table style='width:100%;border-collapse:collapse;margin-bottom:20px'>
    <tr><td style='padding:4px 0;width:40%;color:#666'>" . ( $fr ? 'Besoin d\'installateur' : 'Installer needed' ) . "</td><td><strong>{$installer_label}</strong></td></tr>
    <tr><td style='padding:4px 0;color:#666'>" . ( $fr ? 'Mode de récupération' : 'Pickup method' ) . "</td><td><strong>{$delivery_label}</strong></td></tr>
    <tr><td style='padding:4px 0;color:#666'>" . ( $fr ? 'Rappel téléphonique' : 'Callback' ) . "</td><td><strong>{$callback_label}</strong></td></tr>
    <tr><td style='padding:4px 0;color:#666;vertical-align:top'>{$notes_label}</td><td>" . ( $notes_val ?: '—' ) . "</td></tr>
  </table>
</div>
<div style='background:#f5f5f5;padding:12px 30px;font-size:.85rem;color:#888'>
  " . ( $fr ? 'Envoyé depuis le configurateur Solithium' : 'Sent from the Solithium configurator' ) . " · " . get_site_url() . "
</div></body></html>";
    }

    /* ───────────────────────────────────────────────
       FORMAT COURRIEL — Confirmation client
    ─────────────────────────────────────────────── */
    private static function build_email_client( string $lang, array $d ): string {
        $fr    = $lang === 'fr';
        $phone = $d['phone'] ? ( $fr ? 'Vous pouvez également nous joindre au ' : 'You can also reach us at ' ) . esc_html( $d['phone'] ) . '.' : '';
        $callback_note = ! empty( $d['services']['callback'] )
            ? ( $fr ? '<p>📞 Un membre de notre équipe vous rappellera sous peu au <strong>' . esc_html( $d['services']['phone'] ?? '' ) . '</strong>.</p>'
                    : '<p>📞 A member of our team will call you back shortly at <strong>' . esc_html( $d['services']['phone'] ?? '' ) . '</strong>.</p>' )
            : '';

        $items_html = '';
        foreach ( (array) ( $d['items'] ?? [] ) as $it ) {
            $name  = esc_html( (string) ( $it['name'] ?? '' ) );
            $qty   = (int) ( $it['qty'] ?? 1 );
            $price = (float) ( $it['price'] ?? 0 );
            $items_html .= "<tr><td style='padding:4px 8px'>{$name}</td><td style='padding:4px 8px;text-align:center'>{$qty}</td><td style='padding:4px 8px;text-align:right'>{$d['currency']}" . number_format( $qty * $price, 2 ) . "</td></tr>";
        }

        $order_info = ! empty( $d['order_id'] )
            ? '<p><strong>' . ( $fr ? 'Commande WooCommerce créée' : 'WooCommerce order created' ) . ' #' . (int) $d['order_id'] . '</strong></p>'
            : '';
        $links_html = '';
        if ( ! empty( $d['cart_url'] ) || ! empty( $d['account_url'] ) || ! empty( $d['shop_url'] ) ) {
            $links_html .= '<p>';
            if ( ! empty( $d['cart_url'] ) ) {
                $links_html .= '<a href="' . esc_url( $d['cart_url'] ) . '">' . ( $fr ? 'Voir le panier' : 'View cart' ) . '</a> · ';
            }
            if ( ! empty( $d['account_url'] ) ) {
                $links_html .= '<a href="' . esc_url( $d['account_url'] ) . '">' . ( $fr ? 'Mon compte' : 'My account' ) . '</a> · ';
            }
            if ( ! empty( $d['shop_url'] ) ) {
                $links_html .= '<a href="' . esc_url( $d['shop_url'] ) . '">' . ( $fr ? 'Boutique' : 'Shop' ) . '</a>';
            }
            $links_html .= '</p>';
        }

        $customer_service_block = "<div style='background:#f7f9fb;border:1px solid #dfe6ee;padding:12px 16px;margin-top:14px;border-radius:4px'>"
            . '<strong>' . ( $fr ? 'Service à la clientèle' : 'Customer Service' ) . "</strong><br>"
            . esc_html( (string) ( $d['phone'] ?? '' ) )
            . "<br><strong>Solithium</strong></div>";

        return "<!DOCTYPE html><html><body style='font-family:Arial,sans-serif;color:#222;max-width:600px;margin:auto'>
<div style='background:#1a2332;padding:20px 30px'>
  <h1 style='color:#f5a623;margin:0;font-size:1.3rem'>☀ Solithium</h1>
</div>
<div style='padding:24px 30px'>
  <p>" . ( $fr ? "Bonjour <strong>" . esc_html( $d['client_name'] ) . "</strong>," : "Hello <strong>" . esc_html( $d['client_name'] ) . "</strong>," ) . "</p>
  <p>" . ( $fr ? 'Nous avons bien reçu votre demande de configuration solaire. Notre équipe va l\'examiner et communiquera avec vous dans les plus brefs délais.'
               : 'We have received your solar configuration request. Our team will review it and get back to you as soon as possible.' ) . "</p>
  {$callback_note}
  {$order_info}
  {$links_html}
  <table style='width:100%;border-collapse:collapse;border:1px solid #ddd;margin:12px 0 20px'>
    <thead><tr style='background:#2e8b57;color:#fff'>
      <th style='padding:6px 8px;text-align:left'>" . ( $fr ? 'Produit' : 'Product' ) . "</th>
      <th style='padding:6px 8px;text-align:center'>Qté</th>
      <th style='padding:6px 8px;text-align:right'>" . ( $fr ? 'Montant' : 'Amount' ) . "</th>
    </tr></thead>
    <tbody>{$items_html}</tbody>
  </table>
  <div style='background:#e8f5ee;border-left:4px solid #2e8b57;padding:12px 16px;margin:20px 0;border-radius:4px'>
    <strong>" . ( $fr ? 'Total estimé de votre sélection :' : 'Estimated total of your selection:' ) . " " . $d['currency'] . number_format( $d['grand_total'], 2 ) . "</strong>
  </div>
  <p style='font-size:.9rem;color:#666'>{$phone}</p>
  <p style='font-size:.9rem;color:#666'>" . ( $fr ? 'Merci de votre confiance !' : 'Thank you for your trust!' ) . "</p>
  {$customer_service_block}
</div>
<div style='background:#f5f5f5;padding:12px 30px;font-size:.8rem;color:#999'>
  Solithium 2 · " . get_site_url() . "
</div></body></html>";
    }

    /**
     * Crée une commande WooCommerce pending à partir des items wizard.
     */
    private static function create_wc_order_from_items( array $items, int $user_id, array $services, string $client_email ): int {
        try {
            $order = wc_create_order( [ 'customer_id' => max( 0, $user_id ) ] );
            if ( ! $order ) {
                return 0;
            }

            foreach ( $items as $item ) {
                $resolved = self::resolve_wc_target_from_item( $item );
                $product_id = (int) ( $resolved['product_id'] ?? 0 );
                $variation_id = (int) ( $resolved['variation_id'] ?? 0 );
                $variation_attrs = (array) ( $resolved['variation_attrs'] ?? [] );

                if ( $product_id ) {
                    $product_obj = $variation_id > 0 ? wc_get_product( $variation_id ) : wc_get_product( $product_id );

                    if ( $product_obj ) {
                        $qty = max( 1, (int) ( $item['qty'] ?? 1 ) );
                        $args = [];
                        if ( $variation_id > 0 ) {
                            $args['variation_id'] = $variation_id;
                            $args['variation']    = $variation_attrs;
                        }
                        $order->add_product( $product_obj, $qty, $args );
                    }
                } else {
                    // Fallback: conserver la trace de la ligne dans la commande
                    // même si aucun produit WC exact n'a pu être résolu.
                    $line_name = sanitize_text_field( (string) ( $item['name'] ?? 'Item' ) );
                    $qty = max( 1, (int) ( $item['qty'] ?? 1 ) );
                    $line_total = (float) ( $item['price'] ?? 0 ) * $qty;
                    if ( $line_total > 0 ) {
                        $fee = new WC_Order_Item_Fee();
                        $fee->set_name( $line_name . ( $qty > 1 ? " × {$qty}" : '' ) );
                        $fee->set_amount( $line_total );
                        $fee->set_total( $line_total );
                        $order->add_item( $fee );
                    }
                }
            }

            if ( ! $order->get_items() ) {
                $order->delete( true );
                return 0;
            }

            if ( $client_email ) {
                $order->set_billing_email( sanitize_email( $client_email ) );
            }

            $order->update_meta_data( '_slwiz_service_installer', sanitize_text_field( (string) ( $services['installer'] ?? '' ) ) );
            $order->update_meta_data( '_slwiz_service_delivery', sanitize_text_field( (string) ( $services['delivery'] ?? '' ) ) );
            $order->update_meta_data( '_slwiz_service_callback', ! empty( $services['callback'] ) ? '1' : '0' );
            $order->update_meta_data( '_slwiz_service_phone', sanitize_text_field( (string) ( $services['phone'] ?? '' ) ) );
            $order->update_meta_data( '_slwiz_service_notes', sanitize_textarea_field( (string) ( $services['notes'] ?? '' ) ) );

            $order->calculate_totals();
            $order->set_status( 'pending' );
            $order->save();

            return (int) $order->get_id();
        } catch ( \Throwable $e ) {
            return 0;
        }
    }

    /**
     * Résout un item wizard vers une cible WooCommerce.
     * Priorité: wc_product_id > SKU > nom produit.
     *
     * @return array{product_id:int,variation_id:int,variation_attrs:array}
     */
    private static function resolve_wc_target_from_item( array $item ): array {
        $product_id = (int) ( $item['wc_product_id'] ?? 0 );
        $variation_id = (int) ( $item['wc_variation_id'] ?? 0 );
        $variation_attrs = $item['wc_variation_attrs'] ?? [];
        if ( ! is_array( $variation_attrs ) ) {
            $variation_attrs = [];
        }
        $variation_attrs = array_map( 'wc_clean', $variation_attrs );

        if ( ! $product_id && function_exists( 'wc_get_product_id_by_sku' ) ) {
            $sku = sanitize_text_field( (string) ( $item['sku'] ?? '' ) );
            if ( $sku !== '' ) {
                $product_id = (int) wc_get_product_id_by_sku( $sku );
            }
        }

        // Fallback de correspondance par nom de produit
        if ( ! $product_id ) {
            $name = sanitize_text_field( (string) ( $item['name'] ?? '' ) );
            if ( $name !== '' ) {
                $matched = wc_get_products( [
                    'status' => 'publish',
                    'limit'  => 1,
                    'search' => $name,
                    'return' => 'ids',
                ] );
                if ( ! empty( $matched ) ) {
                    $product_id = (int) $matched[0];
                }
            }
        }

        // Si ID résout une variation, convertir vers (parent + variation)
        if ( $product_id ) {
            $product_obj = wc_get_product( $product_id );
            if ( $product_obj && $product_obj->is_type( 'variation' ) ) {
                $variation_id    = (int) $product_obj->get_id();
                $variation_attrs = (array) $product_obj->get_variation_attributes();
                $product_id      = (int) $product_obj->get_parent_id();
            }
        }

        return [
            'product_id'      => $product_id,
            'variation_id'    => $variation_id,
            'variation_attrs' => $variation_attrs,
        ];
    }

    /**
     * Sauvegarde le devis dans le compte utilisateur (max 10).
     */
    private static function save_user_quote( int $user_id, array $quote ): void {
        if ( $user_id <= 0 ) {
            return;
        }

        $quotes = get_user_meta( $user_id, 'slwiz_quotes', true );
        if ( ! is_array( $quotes ) ) {
            $quotes = [];
        }

        array_unshift( $quotes, $quote );
        $quotes = array_slice( $quotes, 0, 10 );

        update_user_meta( $user_id, 'slwiz_quotes', $quotes );
    }

    /* ───────────────────────────────────────────────
       UTILITAIRES PRIVÉS
    ─────────────────────────────────────────────── */
    private static function get_or_create_session(): string {
        $key = isset( $_COOKIE['slwiz_session'] )
            ? sanitize_text_field( $_COOKIE['slwiz_session'] )
            : wp_generate_uuid4();
        setcookie( 'slwiz_session', $key, time() + 2 * HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
        return $key;
    }

    private static function unique_username( string $base ): string {
        $username = $base;
        $i = 1;
        while ( username_exists( $username ) ) {
            $username = $base . $i;
            $i++;
        }
        return $username;
    }
}

// Initialiser les hooks
Solithium_Ajax::init();
