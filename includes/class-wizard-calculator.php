<?php
/**
 * Solithium_Calculator
 * Calculs de dimensionnement photovoltaïque / Solar sizing calculations
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Solithium_Calculator {

    /* ───────────────────────────────────────────────
       DONNÉES RÉGIONALES — heures de pic solaire (PSH)
       Source : données moyennes annuelles, NASA POWER,
                adaptées aux régions du Québec.
    ─────────────────────────────────────────────── */
    public static function get_regions(): array {
        return [
            'montreal'    => [ 'psh' => 3.8, 'lat' => 45.5, 'label_fr' => 'Montréal / Laval / Rive-Sud',         'label_en' => 'Montreal / Laval / South Shore' ],
            'quebec'      => [ 'psh' => 3.5, 'lat' => 46.8, 'label_fr' => 'Québec / Chaudière-Appalaches',        'label_en' => 'Quebec City / Chaudiere-Appalaches' ],
            'troisriv'    => [ 'psh' => 3.6, 'lat' => 46.3, 'label_fr' => 'Mauricie / Trois-Rivières',             'label_en' => 'Mauricie / Trois-Rivières' ],
            'saguenay'    => [ 'psh' => 3.3, 'lat' => 48.4, 'label_fr' => 'Saguenay–Lac-Saint-Jean',               'label_en' => 'Saguenay–Lac-Saint-Jean' ],
            'sherbrooke'  => [ 'psh' => 3.8, 'lat' => 45.4, 'label_fr' => 'Estrie / Sherbrooke',                   'label_en' => 'Eastern Townships / Sherbrooke' ],
            'rimouski'    => [ 'psh' => 3.2, 'lat' => 48.4, 'label_fr' => 'Bas-Saint-Laurent / Gaspésie',          'label_en' => 'Lower Saint Lawrence / Gaspesie' ],
            'abitibi'     => [ 'psh' => 3.1, 'lat' => 48.5, 'label_fr' => 'Abitibi-Témiscamingue / Outaouais',     'label_en' => 'Abitibi-Temiscamingue / Outaouais' ],
            'nordquebec'  => [ 'psh' => 2.8, 'lat' => 55.0, 'label_fr' => 'Nord-du-Québec / Côte-Nord',            'label_en' => 'Northern Quebec / North Shore' ],
            'laurentides' => [ 'psh' => 3.5, 'lat' => 46.0, 'label_fr' => 'Laurentides / Lanaudière / Outaouais',  'label_en' => 'Laurentians / Lanaudiere / Outaouais' ],
        ];
    }

    /* ───────────────────────────────────────────────
       CALCUL — Nouvelle installation
    ─────────────────────────────────────────────── */
    public static function calculate_new( array $data ): array {
        $appliances   = $data['appliances']    ?? [];
        $region       = $data['region']        ?? 'montreal';
        $autonomy     = (int)($data['autonomy_days'] ?? 2);
        $install_area = (float)($data['install_area'] ?? 0);
        $usage_type   = $data['usage_type']    ?? 'residential';

        // 1. Consommation journalière totale (Wh/j)
        $daily_wh = 0;
        $max_single_load = 0;
        foreach ( $appliances as $app ) {
            $power = (float)( $app['power_w']      ?? 0 );
            $hours = (float)( $app['hours_per_day'] ?? 0 );
            $daily_wh += $power * $hours;
            if ( $power > $max_single_load ) $max_single_load = $power;
        }

        // 2. PSH régionale
        $regions = self::get_regions();
        $psh = $regions[ $region ]['psh'] ?? 3.5;

        // 3. Rendement système global (câblage + contrôleur + onduleur + salissures)
        $efficiency = 0.77;

        // 4. Capacité de panneaux nécessaire (W-crête)
        // Facteur de sécurité 1.25 pour marges de perte
        $panel_w_raw = $daily_wh > 0
            ? ( $daily_wh * 1.25 ) / ( $psh * $efficiency )
            : 0;

        // 5. Tension du système selon puissance
        $voltage = self::determine_voltage( $panel_w_raw );

        // 6. Capacité batterie (LiFePO4, DOD 80%)
        $batt_ah  = $daily_wh > 0
            ? ( $daily_wh * $autonomy ) / ( $voltage * 0.80 )
            : 0;
        $batt_kwh = ( $batt_ah * $voltage ) / 1000;

        // 7. Puissance onduleur = charge simultanée max × 1.25, min 600 W
        // Estimation : charge max ≈ plus grand appareil + 40% du reste
        $other_loads  = $daily_wh * 0.4;  // estimation charge simultanée des autres appareils
        $inverter_raw = ( $max_single_load + $other_loads ) * 1.25;
        $inverter_w   = max( 600, round( $inverter_raw ) );

        // 8. Régulateur MPPT (ampères)
        $controller_a = ceil( ( $panel_w_raw * 1.25 ) / $voltage );

        // 9. Surface de panneaux nécessaire (~200 W/m² pour mono standard)
        $panel_area_m2 = $panel_w_raw / 200;

        // 10. Analyse surface
        $area_ok = $install_area > 0 && $install_area >= $panel_area_m2;

        return [
            'daily_wh'          => round( $daily_wh ),
            'daily_kwh'         => round( $daily_wh / 1000, 2 ),
            'monthly_kwh'       => round( $daily_wh / 1000 * 30, 1 ),
            'psh'               => $psh,
            'system_voltage'    => $voltage,
            'panel_capacity_w'  => round( $panel_w_raw ),
            'batt_capacity_ah'  => round( $batt_ah ),
            'batt_capacity_kwh' => round( $batt_kwh, 1 ),
            'inverter_w'        => $inverter_w,
            'controller_a'      => $controller_a,
            'panel_area_m2'     => round( $panel_area_m2, 1 ),
            'area_available_m2' => $install_area,
            'area_sufficient'   => $area_ok,
            'autonomy_days'     => $autonomy,
            'region'            => $region,
            'usage_type'        => $usage_type,
            'appliances_count'  => count( $appliances ),
        ];
    }

    /* ───────────────────────────────────────────────
       CALCUL — Remplacement / Mise à niveau
    ─────────────────────────────────────────────── */
    public static function calculate_upgrade( array $data ): array {
        $existing  = $data['existing']    ?? [];
        $to_replace = $data['to_replace'] ?? [];
        $usage_type = $data['usage_type'] ?? 'static';

        // Caractériser l'installation existante
        $panels_w   = 0;
        foreach ( $existing['panels'] ?? [] as $p ) {
            $panels_w += (int)($p['qty'] ?? 1) * (int)($p['watts'] ?? 0);
        }

        $batt_total_wh = 0;
        foreach ( $existing['batteries'] ?? [] as $b ) {
            $qty      = (int)($b['qty']    ?? 1);
            $ah       = (float)($b['ah']   ?? 0);
            $volts    = (int)($b['volts']  ?? 12);
            $batt_total_wh += $qty * $ah * $volts;
        }

        $inverter_w   = (int)($existing['inverter']['watts']      ?? 0);
        $controller_a = (int)($existing['controller']['amps']     ?? 0);
        $system_v     = (int)($existing['system_voltage']         ?? 12);

        return [
            'existing_panel_w'    => $panels_w,
            'existing_batt_wh'    => round( $batt_total_wh ),
            'existing_batt_kwh'   => round( $batt_total_wh / 1000, 1 ),
            'existing_inverter_w' => $inverter_w,
            'existing_controller_a' => $controller_a,
            'system_voltage'      => $system_v,
            'to_replace'          => $to_replace,
            'usage_type'          => $usage_type,
        ];
    }

    /* ───────────────────────────────────────────────
       UTILITAIRES
    ─────────────────────────────────────────────── */
    private static function determine_voltage( float $panel_w ): int {
        if ( $panel_w <= 400  ) return 12;
        if ( $panel_w <= 1800 ) return 24;
        return 48;
    }
}
