<?php
/**
 * Solithium_Admin
 * Page de réglages WordPress — gestion des accessoires et du courriel de notification
 * WordPress settings page — accessories management and notification email
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Solithium_Admin {

    public static function init(): void {
        add_action( 'admin_menu',   [ __CLASS__, 'add_menu' ] );
        add_action( 'admin_init',   [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
    }

    /* ───────────────────────────────────────────────
       MENU WORDPRESS
    ─────────────────────────────────────────────── */
    public static function add_menu(): void {
        add_options_page(
            'Solithium Wizard — Réglages',
            '☀ Solithium Wizard',
            'manage_options',
            'solithium-wizard-settings',
            [ __CLASS__, 'settings_page' ]
        );
    }

    /* ───────────────────────────────────────────────
       ENREGISTREMENT DES OPTIONS
    ─────────────────────────────────────────────── */
    public static function register_settings(): void {
        register_setting( 'slwiz_settings_group', 'slwiz_notification_email', [
            'sanitize_callback' => 'sanitize_email',
            'default'           => get_option( 'admin_email' ),
        ] );
        register_setting( 'slwiz_settings_group', 'slwiz_contact_phone', [
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ] );
        register_setting( 'slwiz_settings_group', 'slwiz_accessories_json', [
            'sanitize_callback' => [ __CLASS__, 'sanitize_accessories_json' ],
            'default'           => wp_json_encode( self::default_accessories() ),
        ] );
    }

    /* ───────────────────────────────────────────────
       ASSETS ADMIN
    ─────────────────────────────────────────────── */
    public static function enqueue_admin_assets( string $hook ): void {
        if ( 'settings_page_solithium-wizard-settings' !== $hook ) return;
        wp_enqueue_style( 'slwiz-admin', SLWIZ_URL . 'assets/css/wizard-admin.css', [], SLWIZ_VERSION );
    }

    /* ───────────────────────────────────────────────
       ACCESSOIRES PAR DÉFAUT
    ─────────────────────────────────────────────── */
    public static function default_accessories(): array {
        return [
            [ 'id' => 'acc-monitor',    'sku' => 'SOL-MON-01',  'name_fr' => 'Système de monitoring WiFi',             'name_en' => 'WiFi Monitoring System',          'price' => 149.00 ],
            [ 'id' => 'acc-disconnect', 'sku' => 'SOL-DIS-01',  'name_fr' => 'Disjoncteur de batterie 200A',           'name_en' => 'Battery Disconnect Switch 200A',  'price' => 39.00  ],
            [ 'id' => 'acc-surge',      'sku' => 'SOL-SRG-01',  'name_fr' => 'Protecteur de surtension DC',            'name_en' => 'DC Surge Protection Device',      'price' => 59.00  ],
            [ 'id' => 'acc-box',        'sku' => 'SOL-BOX-01',  'name_fr' => 'Coffret de jonction étanche IP65',       'name_en' => 'Weatherproof Junction Box IP65',  'price' => 49.00  ],
            [ 'id' => 'acc-meter',      'sku' => 'SOL-MTR-01',  'name_fr' => 'Compteur / ampèremètre Bluetooth',       'name_en' => 'Bluetooth Battery Monitor',       'price' => 79.00  ],
            [ 'id' => 'acc-ground',     'sku' => 'SOL-GND-01',  'name_fr' => 'Kit de mise à la terre (grounding)',     'name_en' => 'Grounding Kit',                   'price' => 35.00  ],
            [ 'id' => 'acc-fuse',       'sku' => 'SOL-FSB-01',  'name_fr' => 'Boîtier de fusibles PV (strings)',       'name_en' => 'PV String Fuse Box',              'price' => 69.00  ],
        ];
    }

    /* ───────────────────────────────────────────────
       ACCESSOIRES ENREGISTRÉS (depuis wp_options)
    ─────────────────────────────────────────────── */
    public static function get_accessories(): array {
        $json = get_option( 'slwiz_accessories_json', '' );
        if ( empty( $json ) ) return self::default_accessories();
        $decoded = json_decode( $json, true );
        return is_array( $decoded ) ? $decoded : self::default_accessories();
    }

    /* ───────────────────────────────────────────────
       SANITISATION DU JSON ACCESSOIRES
    ─────────────────────────────────────────────── */
    public static function sanitize_accessories_json( $value ): string {
        $arr = json_decode( wp_unslash( $value ), true );
        if ( ! is_array( $arr ) ) return wp_json_encode( self::default_accessories() );
        $clean = [];
        foreach ( $arr as $item ) {
            if ( empty( $item['name_fr'] ) ) continue;
            $clean[] = [
                'id'      => sanitize_key( $item['id']      ?? '' ),
                'sku'     => sanitize_text_field( $item['sku']     ?? '' ),
                'name_fr' => sanitize_text_field( $item['name_fr'] ?? '' ),
                'name_en' => sanitize_text_field( $item['name_en'] ?? '' ),
                'price'   => round( (float)( $item['price'] ?? 0 ), 2 ),
            ];
        }
        return wp_json_encode( $clean );
    }

    /* ───────────────────────────────────────────────
       PAGE DE RÉGLAGES
    ─────────────────────────────────────────────── */
    public static function settings_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) return;

        // Sauvegarder si soumis
        if ( isset( $_POST['slwiz_save_accessories'] ) ) {
            check_admin_referer( 'slwiz_accessories_nonce' );
            $raw = wp_unslash( $_POST['slwiz_accessories_json'] ?? '[]' );
            update_option( 'slwiz_accessories_json', self::sanitize_accessories_json( $raw ) );
            update_option( 'slwiz_notification_email', sanitize_email( $_POST['slwiz_notification_email'] ?? '' ) );
            update_option( 'slwiz_contact_phone', sanitize_text_field( $_POST['slwiz_contact_phone'] ?? '' ) );
            echo '<div class="notice notice-success"><p>✅ Réglages sauvegardés.</p></div>';
        }

        $email       = get_option( 'slwiz_notification_email', get_option( 'admin_email' ) );
        $phone       = get_option( 'slwiz_contact_phone', '' );
        $accessories = self::get_accessories();
        ?>
        <div class="wrap slwiz-admin-wrap">
            <h1>☀ Solithium Wizard — Réglages</h1>
            <p style="color:#666">Gérez le courriel de notification, le numéro de rappel, et les accessoires optionnels proposés dans le wizard.</p>
            <hr>

            <form method="post" id="slwiz-settings-form">
                <?php wp_nonce_field( 'slwiz_accessories_nonce' ); ?>

                <!-- ── Notifications ───────────────────────────── -->
                <h2 style="color:#2e8b57">📧 Notifications et coordonnées</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="slwiz_notification_email">Courriel de notification</label></th>
                        <td>
                            <input type="email" id="slwiz_notification_email" name="slwiz_notification_email"
                                   value="<?php echo esc_attr( $email ); ?>" class="regular-text">
                            <p class="description">Adresse qui recevra les demandes de devis et les rappels clients.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="slwiz_contact_phone">Téléphone affiché au client</label></th>
                        <td>
                            <input type="text" id="slwiz_contact_phone" name="slwiz_contact_phone"
                                   value="<?php echo esc_attr( $phone ); ?>" class="regular-text" placeholder="ex. 514-555-0100">
                            <p class="description">Numéro affiché dans le message de confirmation après soumission du devis.</p>
                        </td>
                    </tr>
                </table>

                <hr>

                <!-- ── Accessoires ─────────────────────────────── -->
                <h2 style="color:#2e8b57">🔧 Accessoires et options</h2>
                <p>Ces accessoires apparaissent comme options cochables dans l'étape "Solutions" du wizard. Vous pouvez ajouter, modifier ou supprimer des lignes.</p>

                <div id="slwiz-acc-table-wrap">
                    <table class="widefat slwiz-acc-table" id="slwiz-acc-table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nom français</th>
                                <th>Name (English)</th>
                                <th>Prix ($)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="slwiz-acc-tbody">
                            <?php foreach ( $accessories as $idx => $acc ) : ?>
                            <tr class="slwiz-acc-row" data-idx="<?php echo $idx; ?>">
                                <td><input type="text" name="acc_sku[]"     value="<?php echo esc_attr( $acc['sku'] ); ?>"     placeholder="SOL-XXX-01" class="slwiz-acc-input"></td>
                                <td><input type="text" name="acc_name_fr[]" value="<?php echo esc_attr( $acc['name_fr'] ); ?>" placeholder="Nom en français"  class="slwiz-acc-input slwiz-acc-name"></td>
                                <td><input type="text" name="acc_name_en[]" value="<?php echo esc_attr( $acc['name_en'] ); ?>" placeholder="English name"     class="slwiz-acc-input slwiz-acc-name"></td>
                                <td><input type="number" name="acc_price[]" value="<?php echo esc_attr( $acc['price'] ); ?>"   step="0.01" min="0"             class="slwiz-acc-input slwiz-acc-price"></td>
                                <td><button type="button" class="button slwiz-acc-delete" onclick="this.closest('tr').remove()">✕</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Champ caché JSON — rempli avant soumission -->
                <input type="hidden" name="slwiz_accessories_json" id="slwiz-acc-json" value="">

                <p>
                    <button type="button" class="button" id="slwiz-acc-add">+ Ajouter un accessoire</button>
                </p>

                <p class="submit">
                    <button type="submit" name="slwiz_save_accessories" class="button-primary" style="font-size:1rem;padding:.5rem 1.5rem">
                        💾 Enregistrer les réglages
                    </button>
                </p>
            </form>
        </div>

        <style>
            .slwiz-admin-wrap { max-width: 960px; }
            .slwiz-acc-table  { margin-top:.5rem; }
            .slwiz-acc-input  { width:100%; box-sizing:border-box; }
            .slwiz-acc-name   { min-width:180px; }
            .slwiz-acc-price  { max-width:90px; }
            .slwiz-acc-delete { color:#c00 !important; }
        </style>

        <script>
        (function(){
            // Ajouter une ligne
            document.getElementById('slwiz-acc-add').addEventListener('click', function(){
                const tbody = document.getElementById('slwiz-acc-tbody');
                const tr = document.createElement('tr');
                tr.className = 'slwiz-acc-row';
                tr.innerHTML = '<td><input type="text" name="acc_sku[]" placeholder="SOL-XXX-01" class="slwiz-acc-input"></td>'
                    + '<td><input type="text" name="acc_name_fr[]" placeholder="Nom en français" class="slwiz-acc-input slwiz-acc-name"></td>'
                    + '<td><input type="text" name="acc_name_en[]" placeholder="English name" class="slwiz-acc-input slwiz-acc-name"></td>'
                    + '<td><input type="number" name="acc_price[]" value="0" step="0.01" min="0" class="slwiz-acc-input slwiz-acc-price"></td>'
                    + '<td><button type="button" class="button slwiz-acc-delete" onclick="this.closest(\'tr\').remove()">✕</button></td>';
                tbody.appendChild(tr);
            });

            // Sérialiser le tableau en JSON avant soumission
            document.getElementById('slwiz-settings-form').addEventListener('submit', function(){
                const rows = document.querySelectorAll('.slwiz-acc-row');
                const accessories = [];
                rows.forEach(function(row, idx){
                    const sku     = row.querySelector('[name="acc_sku[]"]').value.trim();
                    const name_fr = row.querySelector('[name="acc_name_fr[]"]').value.trim();
                    const name_en = row.querySelector('[name="acc_name_en[]"]').value.trim();
                    const price   = parseFloat(row.querySelector('[name="acc_price[]"]').value) || 0;
                    if (!name_fr) return;
                    accessories.push({
                        id:      'acc-' + (sku || name_fr).toLowerCase().replace(/[^a-z0-9]/g,'-').substring(0,20),
                        sku:     sku,
                        name_fr: name_fr,
                        name_en: name_en || name_fr,
                        price:   price
                    });
                });
                document.getElementById('slwiz-acc-json').value = JSON.stringify(accessories);
            });
        })();
        </script>
        <?php
    }
}

// Initialiser
Solithium_Admin::init();
