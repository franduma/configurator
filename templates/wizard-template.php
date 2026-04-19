<?php
/**
 * Template du Wizard Solithium
 * Monté via Alpine.js — aucune logique PHP ici, seulement le conteneur HTML
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- <div id="solithium-wizard"
     class="slwiz-root"
     x-data="solithiumWizard"
     x-init="init()">
-->
<div id="solithium-wizard" 
     class="slwiz-root" 
     x-data="solithiumWizard" 
     x-init="init()" 
     x-cloak>


    <!-- En-tête / Header -->
    <header class="slwiz-header">
        <div class="slwiz-header-inner">
            <div class="slwiz-brand">
                <span class="slwiz-logo">☀</span>
                <span class="slwiz-brand-name">Solithium</span>
            </div>
            <!-- Toggle langue / Language toggle -->
            <div class="slwiz-lang-toggle">
                <button :class="lang === 'fr' ? 'active' : ''" @click="lang = 'fr'">FR</button>
                <span>/</span>
                <button :class="lang === 'en' ? 'active' : ''" @click="lang = 'en'">EN</button>
            </div>
        </div>
        <!-- Barre de progression / Progress bar -->
        <div class="slwiz-progress" x-show="step > 1 && step <= maxStep()">
            <div class="slwiz-progress-bar" :style="'width:' + progressPct() + '%'"></div>
            <span class="slwiz-progress-label" x-text="__('step') + ' ' + (step - 1) + ' / ' + (maxStep() - 1)"></span>
        </div>
    </header>

    <!-- Corps du wizard / Wizard body -->
    <main class="slwiz-body">

        <!-- ═══ ÉTAPE 1 : Accueil / Welcome ═══════════════ -->
        <section class="slwiz-step" x-show="step === 1">
            <div class="slwiz-welcome">
                <div class="slwiz-welcome-icon">☀️</div>
                <h1 x-text="__('welcomeTitle')"></h1>
                <p x-text="__('welcomeBody')"></p>
                <button class="slwiz-btn-primary slwiz-btn-large" @click="nextStep()">
                    <span x-text="__('start')"></span> →
                </button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 2 : Choix scénario / Scenario ══════ -->
        <section class="slwiz-step" x-show="step === 2">
            <h2 x-text="__('scenarioTitle')"></h2>
            <p x-text="__('scenarioBody')"></p>
            <div class="slwiz-cards">
                <button class="slwiz-card" :class="scenario === 'new' ? 'selected' : ''" @click="scenario = 'new'">
                    <span class="slwiz-card-icon">🔧</span>
                    <strong x-text="__('scenarioNew')"></strong>
                    <small x-text="__('scenarioNewDesc')"></small>
                </button>
                <button class="slwiz-card" :class="scenario === 'existing' ? 'selected' : ''" @click="scenario = 'existing'">
                    <span class="slwiz-card-icon">🔄</span>
                    <strong x-text="__('scenarioExisting')"></strong>
                    <small x-text="__('scenarioExistingDesc')"></small>
                </button>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" :disabled="!scenario" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 3 : Type d'installation / Usage type ═ -->
        <section class="slwiz-step" x-show="step === 3">
            <h2 x-text="__('usageTypeTitle')"></h2>
            <div class="slwiz-cards">
                <button class="slwiz-card" :class="usageType === 'residential' ? 'selected' : ''" @click="usageType = 'residential'">
                    <span class="slwiz-card-icon">🏠</span>
                    <strong x-text="__('usageResidential')"></strong>
                    <small x-text="__('usageResidentialDesc')"></small>
                </button>
                <button class="slwiz-card" :class="usageType === 'commercial' ? 'selected' : ''" @click="usageType = 'commercial'">
                    <span class="slwiz-card-icon">🏢</span>
                    <strong x-text="__('usageCommercial')"></strong>
                    <small x-text="__('usageCommercialDesc')"></small>
                </button>
                <button class="slwiz-card" :class="usageType === 'rv' ? 'selected' : ''" @click="usageType = 'rv'">
                    <span class="slwiz-card-icon">🚐</span>
                    <strong x-text="__('usageRv')"></strong>
                    <small x-text="__('usageRvDesc')"></small>
                </button>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" :disabled="!usageType" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 4A : Région (nouvelle installation) ═══ -->
        <section class="slwiz-step" x-show="step === 4 && scenario === 'new'">
            <h2 x-text="__('regionTitle')"></h2>
            <p x-text="__('regionBody')"></p>
            <div class="slwiz-field">
                <label x-text="__('regionLabel')"></label>
                <select x-model="region" class="slwiz-select">
                    <option value="" x-text="__('selectRegion')"></option>
                    <template x-for="r in regions" :key="r.id">
                        <option :value="r.id" x-text="lang === 'fr' ? r.label_fr : r.label_en"></option>
                    </template>
                </select>
                <div class="slwiz-hint" x-show="region">
                    <span x-text="__('pshLabel')"></span>
                    <strong x-text="selectedRegion()?.psh + ' h/jour'"></strong>
                </div>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" :disabled="!region" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 4B : Composantes existantes (remplacement) ═ -->
        <section class="slwiz-step" x-show="step === 4 && scenario === 'existing'">
            <h2 x-text="__('existingTitle')"></h2>

            <!-- Panneaux existants -->
            <div class="slwiz-group">
                <h3 x-text="__('existingPanels')"></h3>
                <div class="slwiz-inline-fields">
                    <div class="slwiz-field">
                        <label x-text="__('qty')"></label>
                        <input type="number" x-model.number="existing.panels_qty" min="0" max="100" class="slwiz-input slwiz-input-sm">
                    </div>
                    <div class="slwiz-field">
                        <label x-text="__('wattsEach')"></label>
                        <input type="number" x-model.number="existing.panels_w" min="0" max="1000" step="10" class="slwiz-input slwiz-input-sm">
                    </div>
                </div>
                <p class="slwiz-hint" x-show="existing.panels_qty > 0">
                    <span x-text="__('totalPanel')"></span>
                    <strong x-text="(existing.panels_qty * existing.panels_w) + ' W'"></strong>
                </p>
            </div>

            <!-- Batteries existantes -->
            <div class="slwiz-group">
                <h3 x-text="__('existingBatt')"></h3>
                <div class="slwiz-inline-fields">
                    <div class="slwiz-field">
                        <label x-text="__('qty')"></label>
                        <input type="number" x-model.number="existing.batt_qty" min="0" max="50" class="slwiz-input slwiz-input-sm">
                    </div>
                    <div class="slwiz-field">
                        <label x-text="__('battAh')"></label>
                        <input type="number" x-model.number="existing.batt_ah" min="0" max="1000" step="10" class="slwiz-input slwiz-input-sm">
                    </div>
                    <div class="slwiz-field">
                        <label x-text="__('battVolt')"></label>
                        <select x-model.number="existing.batt_volts" class="slwiz-select slwiz-input-sm">
                            <option value="12">12 V</option>
                            <option value="24">24 V</option>
                            <option value="48">48 V</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Onduleur + régulateur -->
            <div class="slwiz-group">
                <h3 x-text="__('existingInvCtrl')"></h3>
                <div class="slwiz-inline-fields">
                    <div class="slwiz-field">
                        <label x-text="__('existingInvW')"></label>
                        <input type="number" x-model.number="existing.inverter_w" min="0" max="20000" step="100" class="slwiz-input slwiz-input-sm">
                    </div>
                    <div class="slwiz-field">
                        <label x-text="__('existingCtrlA')"></label>
                        <input type="number" x-model.number="existing.controller_a" min="0" max="200" class="slwiz-input slwiz-input-sm">
                    </div>
                </div>
            </div>

            <!-- Composantes à remplacer -->
            <div class="slwiz-group">
                <h3 x-text="__('toReplaceTitle')"></h3>
                <div class="slwiz-checkboxes">
                    <template x-for="comp in replaceOptions" :key="comp.id">
                        <label class="slwiz-checkbox">
                            <input type="checkbox" :value="comp.id" x-model="toReplace">
                            <span x-text="lang === 'fr' ? comp.label_fr : comp.label_en"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 5A : Périodes d'utilisation (nouvelle install) ═ -->
        <section class="slwiz-step" x-show="step === 5 && scenario === 'new'">
            <h2 x-text="__('periodsTitle')"></h2>
            <div class="slwiz-field">
                <label x-text="__('periodsMonths')"></label>
                <div class="slwiz-months-grid">
                    <template x-for="m in months" :key="m.id">
                        <button class="slwiz-month-btn"
                                :class="usageMonths.includes(m.id) ? 'selected' : ''"
                                @click="toggleMonth(m.id)"
                                x-text="lang === 'fr' ? m.fr : m.en">
                        </button>
                    </template>
                </div>
            </div>
            <div class="slwiz-field">
                <label x-text="__('dailyHours')"></label>
                <div class="slwiz-range-row">
                    <input type="range" x-model.number="dailyHours" min="1" max="24" step="0.5" class="slwiz-range">
                    <span class="slwiz-range-val" x-text="dailyHours + ' h/j'"></span>
                </div>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" :disabled="usageMonths.length === 0" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 5B : Budget + plainte (remplacement) ═════ -->
        <section class="slwiz-step" x-show="step === 5 && scenario === 'existing'">
            <h2 x-text="__('contextTitle')"></h2>
            <div class="slwiz-field">
                <label x-text="__('contextComplaints')"></label>
                <textarea x-model="existingComplaints" rows="3" class="slwiz-textarea"
                    :placeholder="__('contextPlaceholder')"></textarea>
            </div>
            <div class="slwiz-field">
                <label x-text="__('budgetTitle')"></label>
                <div class="slwiz-inline-fields">
                    <div class="slwiz-field">
                        <label x-text="__('budgetMin')"></label>
                        <input type="number" x-model.number="budgetMin" min="0" step="100" class="slwiz-input">
                    </div>
                    <div class="slwiz-field">
                        <label x-text="__('budgetMax')"></label>
                        <input type="number" x-model.number="budgetMax" min="0" step="100" class="slwiz-input">
                    </div>
                </div>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="triggerCalculate()" x-text="__('calculate')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 6 : Appareils / Appliances (nouvelle install) ══ -->
        <section class="slwiz-step" x-show="step === 6 && scenario === 'new'">
            <h2 x-text="__('appliancesTitle')"></h2>
            <p x-text="__('appliancesBody')"></p>

            <!-- Liste des appareils -->
            <div class="slwiz-appliances-list">
                <template x-for="(app, idx) in appliances" :key="idx">
                    <div class="slwiz-appliance-row">
                        <input type="text" x-model="app.name" class="slwiz-input" :placeholder="__('applianceName')">
                        <div class="slwiz-appliance-fields">
                            <input type="number" x-model.number="app.power_w" min="1" class="slwiz-input slwiz-input-sm" :placeholder="__('watts')">
                            <span>W</span>
                            <input type="number" x-model.number="app.hours_per_day" min="0.1" max="24" step="0.1" class="slwiz-input slwiz-input-sm" :placeholder="__('hours')">
                            <span x-text="__('hDay')"></span>
                        </div>
                        <span class="slwiz-appliance-daily">
                            <span x-text="Math.round(app.power_w * app.hours_per_day) + ' Wh/j'"></span>
                        </span>
                        <button class="slwiz-btn-remove" @click="removeAppliance(idx)" title="Supprimer">✕</button>
                    </div>
                </template>
            </div>

            <!-- Ajout rapide depuis liste prédéfinie -->
            <div class="slwiz-quick-add">
                <label x-text="__('quickAdd')"></label>
                <div class="slwiz-quick-btns">
                    <template x-for="p in presetAppliances" :key="p.name_fr">
                        <button class="slwiz-btn-chip"
                                @click="addPreset(p)"
                                x-text="lang === 'fr' ? p.name_fr : p.name_en"></button>
                    </template>
                </div>
            </div>

            <button class="slwiz-btn-secondary" @click="addAppliance()" x-text="__('addAppliance')"></button>

            <!-- Sous-total -->
            <div class="slwiz-total-bar" x-show="appliances.length > 0">
                <span x-text="__('totalDaily')"></span>
                <strong x-text="totalDailyWh() + ' Wh (' + (totalDailyWh() / 1000).toFixed(2) + ' kWh)'"></strong>
            </div>

            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" :disabled="appliances.length === 0" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 7 : Autonomie / Autonomy ══════════════ -->
        <section class="slwiz-step" x-show="step === 7 && scenario === 'new'">
            <h2 x-text="__('autonomyTitle')"></h2>
            <p x-text="__('autonomyBody')"></p>
            <div class="slwiz-autonomy-row">
                <div class="slwiz-autonomy-buttons">
                    <template x-for="d in [1,2,3,4,5,7]" :key="d">
                        <button class="slwiz-day-btn" :class="autonomyDays === d ? 'selected' : ''"
                                @click="autonomyDays = d">
                            <strong x-text="d"></strong>
                            <small x-text="d === 1 ? __('day') : __('days')"></small>
                        </button>
                    </template>
                </div>
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 8 : Budget ══════════════════════════ -->
        <section class="slwiz-step" x-show="step === 8 && scenario === 'new'">
            <h2 x-text="__('budgetTitle')"></h2>
            <div class="slwiz-inline-fields">
                <div class="slwiz-field">
                    <label x-text="__('budgetMin')"></label>
                    <input type="number" x-model.number="budgetMin" min="0" step="500" class="slwiz-input">
                </div>
                <div class="slwiz-field">
                    <label x-text="__('budgetMax')"></label>
                    <input type="number" x-model.number="budgetMax" min="0" step="500" class="slwiz-input">
                </div>
            </div>
            <p class="slwiz-hint" x-text="__('budgetHint')"></p>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="nextStep()" x-text="__('next')"></button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 9 : Surface d'installation ══════════ -->
        <section class="slwiz-step" x-show="step === 9 && scenario === 'new'">
            <h2 x-text="__('areaTitle')"></h2>
            <p x-text="__('areaBody')"></p>
            <div class="slwiz-field">
                <label x-text="__('areaLabel')"></label>
                <div class="slwiz-range-row">
                    <input type="range" x-model.number="installArea" min="0" max="200" step="1" class="slwiz-range">
                    <span class="slwiz-range-val" x-text="installArea + ' m²'"></span>
                </div>
                <input type="number" x-model.number="installArea" min="0" max="200" class="slwiz-input slwiz-input-sm" style="margin-top:.5rem">
            </div>
            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="prevStep()" x-text="__('back')"></button>
                <button class="slwiz-btn-primary" @click="triggerCalculate()" x-text="__('calculate')"></button>
            </div>
        </section>

        <!-- ═══ CHARGEMENT / Loading ══════════════════════ -->
        <section class="slwiz-step slwiz-loading" x-show="loading">
            <div class="slwiz-spinner"></div>
            <p x-text="__('calculating')"></p>
        </section>

        <!-- ═══ ÉTAPE TEASER + INSCRIPTION ══════════════ -->
        <section class="slwiz-step" x-show="step === 10 && !loading">
            <div class="slwiz-teaser">
                <h2 x-text="__('teaserTitle')"></h2>

                <!-- Résumé visible avant inscription -->
                <div class="slwiz-teaser-grid" x-show="teaser">
                    <div class="slwiz-teaser-card">
                        <div class="slwiz-teaser-icon">⚡</div>
                        <div class="slwiz-teaser-label" x-text="__('teaserDaily')"></div>
                        <div class="slwiz-teaser-value" x-text="(teaser?.daily_kwh ?? '—') + ' kWh/j'"></div>
                    </div>
                    <div class="slwiz-teaser-card">
                        <div class="slwiz-teaser-icon">☀</div>
                        <div class="slwiz-teaser-label" x-text="__('teaserPanel')"></div>
                        <div class="slwiz-teaser-value" x-text="(teaser?.panel_capacity_w ?? '—') + ' W'"></div>
                    </div>
                    <div class="slwiz-teaser-card">
                        <div class="slwiz-teaser-icon">🔋</div>
                        <div class="slwiz-teaser-label" x-text="__('teaserBatt')"></div>
                        <div class="slwiz-teaser-value" x-text="(teaser?.batt_capacity_kwh ?? '—') + ' kWh'"></div>
                    </div>
                    <div class="slwiz-teaser-card">
                        <div class="slwiz-teaser-icon">🔌</div>
                        <div class="slwiz-teaser-label" x-text="__('teaserVolt')"></div>
                        <div class="slwiz-teaser-value" x-text="(teaser?.system_voltage ?? '—') + ' V'"></div>
                    </div>
                </div>

                <!-- Formulaire d'inscription -->
                <div class="slwiz-register-gate" x-show="!isAuthenticated">
                    <div class="slwiz-gate-message">
                        <span>🔒</span>
                        <p x-text="__('gateMessage')"></p>
                    </div>
                    <form class="slwiz-form" @submit.prevent="doRegister()">
                        <div class="slwiz-inline-fields">
                            <div class="slwiz-field">
                                <label x-text="__('firstName')"></label>
                                <input type="text" x-model="regFirstName" required class="slwiz-input">
                            </div>
                            <div class="slwiz-field">
                                <label x-text="__('lastName')"></label>
                                <input type="text" x-model="regLastName" required class="slwiz-input">
                            </div>
                        </div>
                        <div class="slwiz-field">
                            <label x-text="__('email')"></label>
                            <input type="email" x-model="regEmail" required class="slwiz-input">
                        </div>
                        <div class="slwiz-field">
                            <label x-text="__('password')"></label>
                            <input type="password" x-model="regPassword" required minlength="8" class="slwiz-input">
                            <small x-text="__('passwordHint')"></small>
                        </div>
                        <div class="slwiz-error" x-show="regError" x-text="regError"></div>
                        <p class="slwiz-privacy" x-text="__('privacy')"></p>
                        <button type="submit" class="slwiz-btn-primary slwiz-btn-large" x-text="__('registerBtn')"></button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ═══ ÉTAPE 11 : Solutions ══════════════════════ -->
        <section class="slwiz-step" x-show="step === 11 && isAuthenticated && !loading">
            <h2 x-text="__('solutionsTitle')"></h2>
            <p x-text="__('solutionsBody')"></p>

            <!-- Sélecteur de composantes -->
            <div class="slwiz-solutions" x-show="products">

                <!-- Panneaux -->
                <div class="slwiz-solution-group" x-show="products?.panels?.length">
                    <h3>☀ <span x-text="__('panels')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.panel"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="p in products.panels" :key="p.id">
                            <option :value="JSON.stringify(p)"
                                x-text="(lang === 'fr' ? p.name_fr : p.name_en) + ' × ' + p.qty_needed + ' = ' + formatPrice(p.total_price)">
                            </option>
                        </template>
                    </select>
                    <div class="slwiz-product-specs" x-show="selectedProducts.panel" x-html="renderSpecs(selectedProducts.panel, 'panel')"></div>
                </div>

                <!-- Batteries -->
                <div class="slwiz-solution-group" x-show="products?.batteries?.length">
                    <h3>🔋 <span x-text="__('batteries')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.battery"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="b in products.batteries" :key="b.id">
                            <option :value="JSON.stringify(b)"
                                x-text="(lang === 'fr' ? b.name_fr : b.name_en) + ' × ' + b.qty_needed + ' = ' + formatPrice(b.total_price)">
                            </option>
                        </template>
                    </select>
                    <div class="slwiz-product-specs" x-show="selectedProducts.battery" x-html="renderSpecs(selectedProducts.battery, 'battery')"></div>
                </div>

                <!-- Onduleur -->
                <div class="slwiz-solution-group" x-show="products?.inverters?.length">
                    <h3>🔌 <span x-text="__('inverter')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.inverter"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="i in products.inverters" :key="i.id">
                            <option :value="JSON.stringify(i)"
                                x-text="(lang === 'fr' ? i.name_fr : i.name_en) + ' — ' + formatPrice(i.price)">
                            </option>
                        </template>
                    </select>
                    <div class="slwiz-product-specs" x-show="selectedProducts.inverter" x-html="renderSpecs(selectedProducts.inverter, 'inverter')"></div>
                </div>

                <!-- Régulateur -->
                <div class="slwiz-solution-group" x-show="products?.controllers?.length">
                    <h3>⚙ <span x-text="__('controller')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.controller"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="c in products.controllers" :key="c.id">
                            <option :value="JSON.stringify(c)"
                                x-text="(lang === 'fr' ? c.name_fr : c.name_en) + ' — ' + formatPrice(c.price)">
                            </option>
                        </template>
                    </select>
                    <div class="slwiz-product-specs" x-show="selectedProducts.controller" x-html="renderSpecs(selectedProducts.controller, 'controller')"></div>
                </div>

                <!-- Structure de montage -->
                <div class="slwiz-solution-group" x-show="products?.mounting?.length">
                    <h3>🔩 <span x-text="__('mounting')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.mounting"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="m in products.mounting" :key="m.id">
                            <option :value="JSON.stringify(m)"
                                x-text="(lang === 'fr' ? m.name_fr : m.name_en) + ' — ' + formatPrice(m.price)">
                            </option>
                        </template>
                    </select>
                </div>

                <!-- Câblage -->
                <div class="slwiz-solution-group" x-show="products?.cabling?.length">
                    <h3>〰 <span x-text="__('cabling')"></span></h3>
                    <select class="slwiz-select slwiz-select-product" x-model="selectedProducts.cabling"
                            @change="updateTotal()">
                        <option value="" x-text="__('selectProduct')"></option>
                        <template x-for="c in products.cabling" :key="c.id">
                            <option :value="JSON.stringify(c)"
                                x-text="(lang === 'fr' ? c.name_fr : c.name_en) + ' — ' + formatPrice(c.price)">
                            </option>
                        </template>
                    </select>
                </div>

                <!-- ── Accessoires optionnels ───────────────── -->
                <div class="slwiz-solution-group slwiz-accessories-group" x-show="accessories.length > 0">
                    <h3>✚ <span x-text="__('accessoriesTitle')"></span></h3>
                    <p class="slwiz-accessories-desc" x-text="__('accessoriesDesc')"></p>
                    <div class="slwiz-accessories-grid">
                        <template x-for="acc in accessories" :key="acc.id">
                            <label class="slwiz-acc-card" :class="selectedAccessories.includes(acc.id) ? 'selected' : ''">
                                <input type="checkbox"
                                       :value="acc.id"
                                       x-model="selectedAccessories"
                                       @change="updateTotal()"
                                       class="slwiz-acc-checkbox">
                                <div class="slwiz-acc-card-body">
                                    <span class="slwiz-acc-name" x-text="lang === 'fr' ? acc.name_fr : acc.name_en"></span>
                                    <span class="slwiz-acc-price" x-text="formatPrice(acc.price)"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Sous-total courant + bouton Suivant -->
            <div class="slwiz-subtotal-bar">
                <span x-text="__('subtotal')"></span>
                <strong class="slwiz-subtotal-amount" x-text="formatPrice(cartTotal)"></strong>
            </div>

            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="step = 10" x-text="__('back')"></button>
                <button class="slwiz-btn-primary"
                        :disabled="cartTotal <= 0"
                        @click="step = 12; $nextTick(() => { const el = document.getElementById('solithium-wizard'); if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); })"
                        x-text="__('nextToServices')">
                </button>
            </div>
        </section>

        <!-- ═══ ÉTAPE 12 : Services, livraison et confirmation ══ -->
        <section class="slwiz-step" x-show="step === 12 && isAuthenticated && !loading">
            <h2 x-text="__('servicesTitle')"></h2>
            <p x-text="__('servicesBody')"></p>

            <!-- Besoin d'un installateur ? -->
            <div class="slwiz-group">
                <h3 x-text="__('installerTitle')"></h3>
                <div class="slwiz-cards slwiz-cards-sm">
                    <button class="slwiz-card"
                            :class="serviceInstaller === 'yes' ? 'selected' : ''"
                            @click="serviceInstaller = 'yes'">
                        <span class="slwiz-card-icon">🛠</span>
                        <strong x-text="__('installerYes')"></strong>
                        <small x-text="__('installerYesDesc')"></small>
                    </button>
                    <button class="slwiz-card"
                            :class="serviceInstaller === 'no' ? 'selected' : ''"
                            @click="serviceInstaller = 'no'">
                        <span class="slwiz-card-icon">⚙</span>
                        <strong x-text="__('installerNo')"></strong>
                        <small x-text="__('installerNoDesc')"></small>
                    </button>
                </div>
            </div>

            <!-- Mode de récupération -->
            <div class="slwiz-group">
                <h3 x-text="__('deliveryTitle')"></h3>
                <div class="slwiz-cards slwiz-cards-sm">
                    <button class="slwiz-card"
                            :class="serviceDelivery === 'delivery' ? 'selected' : ''"
                            @click="serviceDelivery = 'delivery'">
                        <span class="slwiz-card-icon">🚚</span>
                        <strong x-text="__('deliveryHome')"></strong>
                        <small x-text="__('deliveryHomeDesc')"></small>
                    </button>
                    <button class="slwiz-card"
                            :class="serviceDelivery === 'pickup' ? 'selected' : ''"
                            @click="serviceDelivery = 'pickup'">
                        <span class="slwiz-card-icon">🏪</span>
                        <strong x-text="__('deliveryPickup')"></strong>
                        <small x-text="__('deliveryPickupDesc')"></small>
                    </button>
                </div>
            </div>

            <!-- Rappel téléphonique -->
            <div class="slwiz-group">
                <h3 x-text="__('callbackTitle')"></h3>
                <label class="slwiz-checkbox-large">
                    <input type="checkbox" x-model="serviceCallback" @change="servicePhone = ''">
                    <span x-text="__('callbackYes')"></span>
                </label>
                <div class="slwiz-field" x-show="serviceCallback" style="margin-top:.8rem">
                    <label x-text="__('callbackPhone')"></label>
                    <input type="tel" x-model="servicePhone" class="slwiz-input"
                           :placeholder="__('callbackPhonePlaceholder')">
                </div>
            </div>

            <!-- Notes additionnelles -->
            <div class="slwiz-group">
                <h3 x-text="__('clientNameTitle')"></h3>
                <input type="text" x-model="clientName" class="slwiz-input" required
                       :placeholder="__('clientNamePlaceholder')">
            </div>

            <!-- Notes additionnelles -->
            <div class="slwiz-group">
                <h3 x-text="__('notesTitle')"></h3>
                <textarea x-model="serviceNotes" rows="3" class="slwiz-textarea"
                          :placeholder="__('notesPlaceholder')"></textarea>
            </div>

            <!-- Récapitulatif final -->
            <div class="slwiz-cart-total">
                <h3 style="color:#fff;margin:0 0 1rem" x-text="__('summaryTitle')"></h3>
                <div class="slwiz-cart-lines">
                    <template x-for="line in allCartLines()" :key="line.name">
                        <div class="slwiz-cart-line">
                            <span x-text="line.name + (line.qty > 1 ? ' × ' + line.qty : '')"></span>
                            <span x-text="formatPrice(line.total)"></span>
                        </div>
                    </template>
                </div>
                <div class="slwiz-cart-total-row">
                    <strong x-text="__('total')"></strong>
                    <strong class="slwiz-cart-total-amount" x-text="formatPrice(grandTotal)"></strong>
                </div>

                <div class="slwiz-error" x-show="quoteError" x-text="quoteError"></div>
                <div class="slwiz-cart-success" x-show="quoteSuccess" x-html="quoteSuccess"></div>

                <div x-show="!quoteSuccess">
                    <button class="slwiz-btn-primary slwiz-btn-large"
                            :disabled="!serviceInstaller || !serviceDelivery || !clientName || grandTotal <= 0 || quoteLoading"
                            @click="doFinalizeQuote()"
                            x-text="quoteLoading ? __('sending') : __('finalizeBtn')">
                    </button>
                    <p class="slwiz-confirm-note" x-text="__('finalizeNote')"></p>
                </div>
            </div>

            <div class="slwiz-nav">
                <button class="slwiz-btn-secondary" @click="step = 11; $nextTick(() => { const el = document.getElementById('solithium-wizard'); if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); })" x-text="__('back')"></button>
            </div>
        </section>

    </main><!-- /.slwiz-body -->
</div><!-- /#solithium-wizard -->
    
