<?php
/**
 * Solithium_Products
 * Catalogue de produits — mode démo (données fictives) ou WooCommerce
 * Product catalog — demo mode (mock data) or WooCommerce live products
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Solithium_Products {

    /* ───────────────────────────────────────────────
       CATALOGUE DÉMO
       Chaque produit contient :
         id, category, sku, name_fr, name_en,
         specs (paramètres électriques + physiques),
         price (CAD), voltage, watts / ah / amps
    ─────────────────────────────────────────────── */
    public static function get_demo_catalog(): array {
        return [

            // ── PANNEAUX SOLAIRES ──────────────────
            'panels' => [
                [
                    'id'       => 'PNL-100-12',
                    'sku'      => 'SOL-PNL-100P',
                    'name_fr'  => 'Panneau solaire 100W 12V Polycristallin',
                    'name_en'  => '100W 12V Polycrystalline Solar Panel',
                    'voltage'  => 12,
                    'watts'    => 100,
                    'price'    => 89.00,
                    'specs'    => [
                        'Puissance crête / Peak power'    => '100 Wc',
                        'Tension Voc / Open circuit Voc'  => '22.2 V',
                        'Courant Isc / Short circuit Isc' => '5.88 A',
                        'Tension Vmp / Optimum Vmp'       => '18.6 V',
                        'Courant Imp / Optimum Imp'       => '5.38 A',
                        'Dimensions'                      => '1020 × 550 × 35 mm',
                        'Surface / Area'                  => '0.56 m²',
                        'Poids / Weight'                  => '7.4 kg',
                        'Garantie / Warranty'             => '10 ans / years',
                    ],
                ],
                [
                    'id'       => 'PNL-200-24',
                    'sku'      => 'SOL-PNL-200M',
                    'name_fr'  => 'Panneau solaire 200W 24V Monocristallin',
                    'name_en'  => '200W 24V Monocrystalline Solar Panel',
                    'voltage'  => 24,
                    'watts'    => 200,
                    'price'    => 189.00,
                    'specs'    => [
                        'Puissance crête / Peak power'    => '200 Wc',
                        'Tension Voc / Open circuit Voc'  => '28.4 V',
                        'Courant Isc / Short circuit Isc' => '8.57 A',
                        'Tension Vmp / Optimum Vmp'       => '23.3 V',
                        'Courant Imp / Optimum Imp'       => '8.58 A',
                        'Dimensions'                      => '1350 × 800 × 35 mm',
                        'Surface / Area'                  => '1.08 m²',
                        'Poids / Weight'                  => '13.8 kg',
                        'Garantie / Warranty'             => '12 ans / years',
                    ],
                ],
                [
                    'id'       => 'PNL-400-48',
                    'sku'      => 'SOL-PNL-400M',
                    'name_fr'  => 'Panneau solaire 400W 48V Monocristallin Half-Cut',
                    'name_en'  => '400W 48V Monocrystalline Half-Cut Solar Panel',
                    'voltage'  => 48,
                    'watts'    => 400,
                    'price'    => 349.00,
                    'specs'    => [
                        'Puissance crête / Peak power'    => '400 Wc',
                        'Tension Voc / Open circuit Voc'  => '49.6 V',
                        'Courant Isc / Short circuit Isc' => '10.12 A',
                        'Tension Vmp / Optimum Vmp'       => '41.6 V',
                        'Courant Imp / Optimum Imp'       => '9.61 A',
                        'Dimensions'                      => '1722 × 1134 × 35 mm',
                        'Surface / Area'                  => '1.95 m²',
                        'Poids / Weight'                  => '20.5 kg',
                        'Garantie / Warranty'             => '15 ans / years',
                    ],
                ],
                [
                    'id'       => 'PNL-600-48',
                    'sku'      => 'SOL-PNL-600B',
                    'name_fr'  => 'Panneau solaire 600W Bifacial Monocristallin',
                    'name_en'  => '600W Bifacial Monocrystalline Solar Panel',
                    'voltage'  => 48,
                    'watts'    => 600,
                    'price'    => 549.00,
                    'specs'    => [
                        'Puissance crête / Peak power'    => '600 Wc (+ 30 Wc face arrière)',
                        'Tension Voc / Open circuit Voc'  => '50.4 V',
                        'Courant Isc / Short circuit Isc' => '14.88 A',
                        'Tension Vmp / Optimum Vmp'       => '42.5 V',
                        'Courant Imp / Optimum Imp'       => '14.12 A',
                        'Dimensions'                      => '2094 × 1303 × 30 mm',
                        'Surface / Area'                  => '2.73 m²',
                        'Poids / Weight'                  => '28.2 kg',
                        'Garantie / Warranty'             => '25 ans / years',
                    ],
                ],
            ],

            // ── BATTERIES ─────────────────────────
            'batteries' => [
                [
                    'id'      => 'BAT-12-100',
                    'sku'     => 'SOL-BAT-12-100L',
                    'name_fr' => 'Batterie LiFePO4 12V 100Ah (BMS intégré)',
                    'name_en' => 'LiFePO4 Battery 12V 100Ah (Built-in BMS)',
                    'voltage' => 12,
                    'ah'      => 100,
                    'wh'      => 1280,
                    'price'   => 399.00,
                    'specs'   => [
                        'Chimie / Chemistry'       => 'LiFePO4',
                        'Capacité / Capacity'      => '100 Ah — 1 280 Wh',
                        'Tension nominale / Nominal' => '12.8 V',
                        'Décharge max / Max DoD'   => '80% (recommandé)',
                        'Cycles de vie / Cycles'   => '> 3 000 cycles @ 80% DoD',
                        'Courant max décharge'     => '100 A continu',
                        'BMS'                      => 'Intégré / Built-in',
                        'Poids / Weight'           => '12.0 kg',
                        'Garantie / Warranty'      => '3 ans / years',
                    ],
                ],
                [
                    'id'      => 'BAT-12-200',
                    'sku'     => 'SOL-BAT-12-200L',
                    'name_fr' => 'Batterie LiFePO4 12V 200Ah (BMS intégré)',
                    'name_en' => 'LiFePO4 Battery 12V 200Ah (Built-in BMS)',
                    'voltage' => 12,
                    'ah'      => 200,
                    'wh'      => 2560,
                    'price'   => 749.00,
                    'specs'   => [
                        'Chimie / Chemistry'       => 'LiFePO4',
                        'Capacité / Capacity'      => '200 Ah — 2 560 Wh',
                        'Tension nominale / Nominal' => '12.8 V',
                        'Décharge max / Max DoD'   => '80%',
                        'Cycles de vie / Cycles'   => '> 3 500 cycles @ 80% DoD',
                        'Courant max décharge'     => '200 A continu',
                        'BMS'                      => 'Intégré / Built-in',
                        'Poids / Weight'           => '22.0 kg',
                        'Garantie / Warranty'      => '3 ans / years',
                    ],
                ],
                [
                    'id'      => 'BAT-24-100',
                    'sku'     => 'SOL-BAT-24-100L',
                    'name_fr' => 'Batterie LiFePO4 24V 100Ah (BMS intégré)',
                    'name_en' => 'LiFePO4 Battery 24V 100Ah (Built-in BMS)',
                    'voltage' => 24,
                    'ah'      => 100,
                    'wh'      => 2560,
                    'price'   => 699.00,
                    'specs'   => [
                        'Chimie / Chemistry'       => 'LiFePO4',
                        'Capacité / Capacity'      => '100 Ah — 2 560 Wh',
                        'Tension nominale / Nominal' => '25.6 V',
                        'Décharge max / Max DoD'   => '80%',
                        'Cycles de vie / Cycles'   => '> 3 500 cycles',
                        'Courant max décharge'     => '100 A continu',
                        'BMS'                      => 'Intégré / Built-in',
                        'Poids / Weight'           => '21.5 kg',
                        'Garantie / Warranty'      => '3 ans / years',
                    ],
                ],
                [
                    'id'      => 'BAT-48-100',
                    'sku'     => 'SOL-BAT-48-100L',
                    'name_fr' => 'Batterie LiFePO4 48V 100Ah (BMS intégré)',
                    'name_en' => 'LiFePO4 Battery 48V 100Ah (Built-in BMS)',
                    'voltage' => 48,
                    'ah'      => 100,
                    'wh'      => 4920,
                    'price'   => 1199.00,
                    'specs'   => [
                        'Chimie / Chemistry'       => 'LiFePO4',
                        'Capacité / Capacity'      => '100 Ah — 4 920 Wh',
                        'Tension nominale / Nominal' => '51.2 V',
                        'Décharge max / Max DoD'   => '80%',
                        'Cycles de vie / Cycles'   => '> 4 000 cycles',
                        'Courant max décharge'     => '100 A continu',
                        'BMS'                      => 'Intégré / Built-in',
                        'Poids / Weight'           => '42.0 kg',
                        'Garantie / Warranty'      => '5 ans / years',
                    ],
                ],
            ],

            // ── ONDULEURS / CHARGEURS ──────────────
            'inverters' => [
                [
                    'id'      => 'INV-600-12',
                    'sku'     => 'SOL-INV-600-12',
                    'name_fr' => 'Onduleur Onde Pure 600W 12V',
                    'name_en' => 'Pure Sine Wave Inverter 600W 12V',
                    'voltage' => 12,
                    'watts'   => 600,
                    'price'   => 249.00,
                    'specs'   => [
                        'Puissance continue'       => '600 W',
                        'Puissance crête / Surge'  => '1 200 W',
                        'Tension entrée / Input'   => '12 V DC',
                        'Tension sortie / Output'  => '120 V AC 60 Hz',
                        'Forme d\'onde / Waveform' => 'Onde pure / Pure sine',
                        'Rendement / Efficiency'   => '> 92%',
                        'Ports USB'                => '2 × USB-A 5V 2.1A',
                        'Garantie / Warranty'      => '2 ans / years',
                    ],
                ],
                [
                    'id'      => 'INV-1200-24',
                    'sku'     => 'SOL-INV-1200-24',
                    'name_fr' => 'Onduleur-Chargeur Onde Pure 1200W 24V',
                    'name_en' => 'Pure Sine Inverter-Charger 1200W 24V',
                    'voltage' => 24,
                    'watts'   => 1200,
                    'price'   => 449.00,
                    'specs'   => [
                        'Puissance continue'       => '1 200 W',
                        'Puissance crête / Surge'  => '2 400 W',
                        'Tension entrée / Input'   => '24 V DC',
                        'Tension sortie / Output'  => '120 V AC 60 Hz',
                        'Chargeur intégré'         => '20 A',
                        'Forme d\'onde / Waveform' => 'Onde pure / Pure sine',
                        'Rendement / Efficiency'   => '> 93%',
                        'Garantie / Warranty'      => '2 ans / years',
                    ],
                ],
                [
                    'id'      => 'INV-2400-48',
                    'sku'     => 'SOL-INV-2400-48',
                    'name_fr' => 'Onduleur-Chargeur Onde Pure 2400W 48V',
                    'name_en' => 'Pure Sine Inverter-Charger 2400W 48V',
                    'voltage' => 48,
                    'watts'   => 2400,
                    'price'   => 899.00,
                    'specs'   => [
                        'Puissance continue'       => '2 400 W',
                        'Puissance crête / Surge'  => '4 800 W',
                        'Tension entrée / Input'   => '48 V DC',
                        'Tension sortie / Output'  => '120 V AC 60 Hz',
                        'Chargeur intégré'         => '40 A',
                        'Forme d\'onde / Waveform' => 'Onde pure / Pure sine',
                        'Rendement / Efficiency'   => '> 94%',
                        'Garantie / Warranty'      => '3 ans / years',
                    ],
                ],
                [
                    'id'      => 'INV-3000-48H',
                    'sku'     => 'SOL-INV-3000-48H',
                    'name_fr' => 'Onduleur Hybride MPPT 3000W 48V (tout-en-un)',
                    'name_en' => 'Hybrid MPPT Inverter 3000W 48V (all-in-one)',
                    'voltage' => 48,
                    'watts'   => 3000,
                    'price'   => 1499.00,
                    'specs'   => [
                        'Puissance continue'        => '3 000 W',
                        'Puissance crête / Surge'   => '6 000 W',
                        'Tension entrée / Input'    => '48 V DC',
                        'Tension sortie / Output'   => '120 V AC 60 Hz',
                        'Chargeur intégré'          => '60 A',
                        'MPPT intégré'              => '80 A (max 145 Voc)',
                        'Réseau / Grid-tie option'  => 'Oui / Yes',
                        'Forme d\'onde / Waveform'  => 'Onde pure / Pure sine',
                        'Rendement / Efficiency'    => '> 95%',
                        'Garantie / Warranty'       => '3 ans / years',
                    ],
                ],
            ],

            // ── RÉGULATEURS MPPT ─────────────────
            'controllers' => [
                [
                    'id'      => 'CTRL-20A',
                    'sku'     => 'SOL-CTRL-20',
                    'name_fr' => 'Régulateur MPPT 20A 12/24V',
                    'name_en' => 'MPPT Charge Controller 20A 12/24V',
                    'amps'    => 20,
                    'price'   => 89.00,
                    'specs'   => [
                        'Courant de charge max'     => '20 A',
                        'Tension système'           => '12 V / 24 V (auto)',
                        'Tension PV max / Max PV'   => '50 V',
                        'Panneaux max @ 12V'        => '260 W',
                        'Panneaux max @ 24V'        => '520 W',
                        'Technologie'               => 'MPPT',
                        'Écran LCD'                 => 'Oui / Yes',
                        'Garantie / Warranty'       => '2 ans / years',
                    ],
                ],
                [
                    'id'      => 'CTRL-40A',
                    'sku'     => 'SOL-CTRL-40',
                    'name_fr' => 'Régulateur MPPT 40A 12/24/48V',
                    'name_en' => 'MPPT Charge Controller 40A 12/24/48V',
                    'amps'    => 40,
                    'price'   => 169.00,
                    'specs'   => [
                        'Courant de charge max'     => '40 A',
                        'Tension système'           => '12 / 24 / 48 V (auto)',
                        'Tension PV max / Max PV'   => '150 V',
                        'Panneaux max @ 48V'        => '1 920 W',
                        'Technologie'               => 'MPPT',
                        'Port BT / Bluetooth'       => 'Oui / Yes',
                        'App mobile'                => 'iOS & Android',
                        'Garantie / Warranty'       => '2 ans / years',
                    ],
                ],
                [
                    'id'      => 'CTRL-60A',
                    'sku'     => 'SOL-CTRL-60',
                    'name_fr' => 'Régulateur MPPT 60A 12/24/48V',
                    'name_en' => 'MPPT Charge Controller 60A 12/24/48V',
                    'amps'    => 60,
                    'price'   => 249.00,
                    'specs'   => [
                        'Courant de charge max'     => '60 A',
                        'Tension système'           => '12 / 24 / 48 V (auto)',
                        'Tension PV max / Max PV'   => '150 V',
                        'Panneaux max @ 48V'        => '2 880 W',
                        'Technologie'               => 'MPPT',
                        'Port BT / Bluetooth'       => 'Oui / Yes',
                        'App mobile'                => 'iOS & Android',
                        'Garantie / Warranty'       => '3 ans / years',
                    ],
                ],
                [
                    'id'      => 'CTRL-80A',
                    'sku'     => 'SOL-CTRL-80',
                    'name_fr' => 'Régulateur MPPT 80A 12/24/48V',
                    'name_en' => 'MPPT Charge Controller 80A 12/24/48V',
                    'amps'    => 80,
                    'price'   => 349.00,
                    'specs'   => [
                        'Courant de charge max'     => '80 A',
                        'Tension système'           => '12 / 24 / 48 V (auto)',
                        'Tension PV max / Max PV'   => '150 V',
                        'Panneaux max @ 48V'        => '3 840 W',
                        'Technologie'               => 'MPPT',
                        'Port BT / Bluetooth'       => 'Oui / Yes',
                        'Affichage couleur'         => 'Oui / Yes',
                        'Garantie / Warranty'       => '3 ans / years',
                    ],
                ],
            ],

            // ── STRUCTURE / MONTAGE ──────────────
            'mounting' => [
                [
                    'id'      => 'MNT-ROOF',
                    'sku'     => 'SOL-MNT-ROOF',
                    'name_fr' => 'Kit de fixation toiture (jusqu\'à 6 panneaux)',
                    'name_en' => 'Roof Mounting Kit (up to 6 panels)',
                    'price'   => 299.00,
                    'specs'   => [ 'Type' => 'Toiture tôle / Bardeau / Membrane', 'Charge de vent' => '140 km/h', 'Matériau' => 'Aluminium anodisé', 'Garantie / Warranty' => '10 ans / years' ],
                ],
                [
                    'id'      => 'MNT-GROUND',
                    'sku'     => 'SOL-MNT-GND',
                    'name_fr' => 'Kit structure au sol (jusqu\'à 6 panneaux)',
                    'name_en' => 'Ground Mount Structure Kit (up to 6 panels)',
                    'price'   => 449.00,
                    'specs'   => [ 'Type' => 'Poteaux + rails au sol', 'Angle réglable' => '15° – 45°', 'Matériau' => 'Acier galvanisé', 'Garantie / Warranty' => '10 ans / years' ],
                ],
                [
                    'id'      => 'MNT-RV',
                    'sku'     => 'SOL-MNT-RV',
                    'name_fr' => 'Kit fixation mobile VR / Bateau (2-4 panneaux)',
                    'name_en' => 'Mobile RV / Boat Mount Kit (2-4 panels)',
                    'price'   => 199.00,
                    'specs'   => [ 'Type' => 'Fixation Z-bracket profilé', 'Charge de vent' => '120 km/h', 'Matériau' => 'Aluminium', 'Garantie / Warranty' => '5 ans / years' ],
                ],
            ],

            // ── CÂBLAGE ───────────────────────────
            'cabling' => [
                [
                    'id'      => 'CBL-SMALL',
                    'sku'     => 'SOL-CBL-SM',
                    'name_fr' => 'Kit câblage complet 12V (< 1 kW)',
                    'name_en' => 'Complete 12V Wiring Kit (< 1 kW)',
                    'price'   => 149.00,
                    'specs'   => [ 'Câble PV' => '10 m, 10 AWG', 'Câble batterie' => '2 m, 2 AWG', 'Connecteurs MC4' => 'Inclus / Included', 'Fusibles' => 'Inclus / Included' ],
                ],
                [
                    'id'      => 'CBL-LARGE',
                    'sku'     => 'SOL-CBL-LG',
                    'name_fr' => 'Kit câblage complet 24/48V (< 5 kW)',
                    'name_en' => 'Complete 24/48V Wiring Kit (< 5 kW)',
                    'price'   => 249.00,
                    'specs'   => [ 'Câble PV' => '20 m, 10 AWG', 'Câble batterie' => '2 m, 1/0 AWG', 'Connecteurs MC4' => 'Inclus / Included', 'Fusibles + disjoncteurs' => 'Inclus / Included' ],
                ],
            ],
        ];
    }

    /* ───────────────────────────────────────────────
       FILTRAGE DES PRODUITS RECOMMANDÉS
       Retourne les options pour chaque composante selon
       les besoins calculés.
    ─────────────────────────────────────────────── */
    public static function get_recommendations( array $needs ): array {
        $catalog = self::get_demo_catalog();
        $v       = (int)($needs['system_voltage'] ?? 24);
        $panel_w = (int)($needs['panel_capacity_w'] ?? 0);
        $batt_ah = (float)($needs['batt_capacity_ah'] ?? 0);
        $batt_kwh = (float)($needs['batt_capacity_kwh'] ?? 0);
        $inv_w   = (int)($needs['inverter_w'] ?? 600);
        $ctrl_a  = (int)($needs['controller_a'] ?? 20);
        $usage   = $needs['usage_type'] ?? 'residential';
        $is_rv   = strpos( $usage, 'rv' ) !== false || $usage === 'mobile';

        // ── Panneaux : trouver les options qui couvrent la puissance nécessaire
        $panel_opts = [];
        foreach ( $catalog['panels'] as $p ) {
            // Accepter panneaux compatibles tension OU polyvalents (12V utilisable en série pour 24V, etc.)
            $qty_needed = $panel_w > 0 ? (int)ceil( $panel_w / $p['watts'] ) : 1;
            $total_w    = $qty_needed * $p['watts'];
            $total_area = round( $qty_needed * (float)($p['specs']['Surface / Area'] ?? '0'), 1 );
            $panel_opts[] = array_merge( $p, [
                'qty_needed'  => $qty_needed,
                'total_w'     => $total_w,
                'total_area_m2' => $total_area,
                'total_price' => $qty_needed * $p['price'],
            ]);
        }

        // ── Batteries : options qui couvrent la capacité nécessaire
        $batt_opts = [];
        foreach ( $catalog['batteries'] as $b ) {
            $qty_needed = $batt_ah > 0 ? (int)ceil( $batt_ah / $b['ah'] ) : 1;
            $total_kwh  = round( $qty_needed * $b['wh'] / 1000, 1 );
            $batt_opts[] = array_merge( $b, [
                'qty_needed'  => $qty_needed,
                'total_kwh'   => $total_kwh,
                'total_price' => $qty_needed * $b['price'],
            ]);
        }

        // ── Onduleurs : ceux dont la puissance ≥ besoins
        $inv_opts = array_filter( $catalog['inverters'], fn($i) => $i['watts'] >= $inv_w );
        $inv_opts = array_values( $inv_opts );
        if ( empty( $inv_opts ) ) $inv_opts = $catalog['inverters'];

        // ── Régulateurs : ceux dont les ampères ≥ besoins
        $ctrl_opts = array_filter( $catalog['controllers'], fn($c) => $c['amps'] >= $ctrl_a );
        $ctrl_opts = array_values( $ctrl_opts );
        if ( empty( $ctrl_opts ) ) $ctrl_opts = array_slice( $catalog['controllers'], -1 );

        // ── Montage selon type d'installation
        if ( $is_rv ) {
            $mount_opts = array_filter( $catalog['mounting'], fn($m) => $m['id'] === 'MNT-RV' );
        } else {
            $mount_opts = array_filter( $catalog['mounting'], fn($m) => $m['id'] !== 'MNT-RV' );
        }
        $mount_opts = array_values( $mount_opts );

        // ── Câblage selon tension
        $cable_opts = $v <= 12
            ? [ $catalog['cabling'][0] ]
            : [ $catalog['cabling'][1] ];

        return [
            'panels'      => $panel_opts,
            'batteries'   => $batt_opts,
            'inverters'   => $inv_opts,
            'controllers' => $ctrl_opts,
            'mounting'    => $mount_opts,
            'cabling'     => $cable_opts,
        ];
    }
}
