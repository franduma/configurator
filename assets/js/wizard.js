/**
 * Solithium Solar Wizard — Alpine.js Component
 * Bilingue FR/EN · Version 1.0.0
 *
 * IMPORTANT : Ce fichier doit être chargé AVANT Alpine.js (defer).
 * WordPress le charge via wp_enqueue_script avec l'attribut defer.
 */

/* ───────────────────────────────────────────────
   TRADUCTIONS / TRANSLATIONS
─────────────────────────────────────────────── */
const SLWIZ_STRINGS = {
  fr: {
    // Navigation
    back: 'Retour', next: 'Suivant', step: 'Étape',
    calculate: 'Calculer mes besoins →',
    calculating: 'Calcul en cours…',
    start: 'Démarrer',
    day: 'jour', days: 'jours',

    // Étape 1 — Accueil
    welcomeTitle: 'Configurez votre système solaire',
    welcomeBody: 'Répondez à quelques questions et nous vous proposerons les composantes adaptées à vos besoins. Le wizard prend environ 3 minutes.',

    // Étape 2 — Scénario
    scenarioTitle: 'Quel est votre projet ?',
    scenarioBody: 'Sélectionnez la situation qui correspond le mieux à votre contexte.',
    scenarioNew: 'Nouvelle installation',
    scenarioNewDesc: 'Vous partez de zéro — pas d\'équipement solaire actuellement.',
    scenarioExisting: 'Mise à niveau',
    scenarioExistingDesc: 'Vous avez déjà une installation et souhaitez remplacer ou ajouter des composantes.',

    // Étape 3 — Type d'usage
    usageTypeTitle: 'Type d\'installation',
    usageResidential: 'Résidentiel fixe',
    usageResidentialDesc: 'Maison, chalet, bâtiment permanent.',
    usageCommercial: 'Commercial / PME',
    usageCommercialDesc: 'Bureau, entrepôt, installation industrielle légère.',
    usageRv: 'Itinérant (VR / Bateau)',
    usageRvDesc: 'Véhicule récréatif, roulotte, voilier, chalet mobile.',

    // Étape 4 — Région
    regionTitle: 'Où sera située l\'installation ?',
    regionBody: 'La région détermine le nombre moyen d\'heures de soleil utilisables (PSH).',
    regionLabel: 'Région du Québec',
    selectRegion: '— Choisir une région —',
    pshLabel: 'Heures de pic solaire (moyenne annuelle) :',

    // Étape 4B — Existant
    existingTitle: 'Décrivez votre installation actuelle',
    existingPanels: 'Panneaux solaires existants',
    existingBatt: 'Batteries existantes',
    existingInvCtrl: 'Onduleur et régulateur',
    existingInvW: 'Puissance onduleur (W)',
    existingCtrlA: 'Ampérage régulateur (A)',
    qty: 'Quantité',
    wattsEach: 'Watts chacun',
    battAh: 'Ampère-heures (Ah)',
    battVolt: 'Tension (V)',
    totalPanel: 'Puissance totale :',
    toReplaceTitle: 'Composante(s) à remplacer ou ajouter',

    // Étape 5 — Périodes
    periodsTitle: 'Périodes d\'utilisation',
    periodsMonths: 'Mois d\'utilisation (sélectionnez tous les mois applicables)',
    dailyHours: 'Heures d\'utilisation quotidienne estimées',

    // Étape 5B — Contexte existant
    contextTitle: 'Contexte d\'utilisation',
    contextComplaints: 'Problèmes constatés avec l\'installation actuelle (optionnel)',
    contextPlaceholder: 'Ex. : batteries se déchargent trop vite, panneaux insuffisants en hiver, onduleur surchauffe…',
    budgetTitle: 'Budget estimé',
    budgetMin: 'Minimum ($)',
    budgetMax: 'Maximum ($)',
    budgetHint: 'Facultatif — nous filtrerons les solutions dans votre fourchette de budget.',

    // Étape 6 — Appareils
    appliancesTitle: 'Appareils et consommations',
    appliancesBody: 'Listez les appareils que vous alimenterez. Indiquez leur puissance en watts et leur durée d\'utilisation quotidienne.',
    applianceName: 'Nom de l\'appareil',
    watts: 'Watts',
    hours: 'H/j',
    hDay: 'h/j',
    addAppliance: '+ Ajouter un appareil',
    quickAdd: 'Ajout rapide :',
    totalDaily: 'Consommation totale estimée :',

    // Étape 7 — Autonomie
    autonomyTitle: 'Autonomie en cas de mauvais temps',
    autonomyBody: 'Combien de jours souhaitez-vous pouvoir fonctionner sans ensoleillement suffisant ? Les batteries seront dimensionnées en conséquence.',

    // Étape 9 — Surface
    areaTitle: 'Surface d\'installation disponible',
    areaBody: 'Superficie disponible pour poser les panneaux (toiture, sol ou toit du VR).',
    areaLabel: 'Surface disponible (m²)',

    // Teaser
    teaserTitle: 'Vos besoins sont calculés !',
    teaserDaily: 'Consommation journalière',
    teaserPanel: 'Puissance de panneaux',
    teaserBatt: 'Stockage batterie',
    teaserVolt: 'Tension système',

    // Inscription
    gateMessage: 'Créez un compte gratuit pour accéder aux produits recommandés, aux prix et pour ajouter votre sélection au panier.',
    firstName: 'Prénom',
    lastName: 'Nom',
    email: 'Courriel',
    password: 'Mot de passe',
    passwordHint: 'Minimum 8 caractères',
    privacy: 'Vos données sont utilisées uniquement pour votre devis. Aucun envoi de courriels non sollicités.',
    registerBtn: 'Créer mon compte et voir les solutions →',

    // Solutions
    solutionsTitle: 'Solutions recommandées',
    solutionsBody: 'Choisissez une option pour chaque composante. Le total est mis à jour automatiquement.',
    selectProduct: '— Sélectionner —',
    panels: 'Panneaux solaires',
    batteries: 'Batteries',
    inverter: 'Onduleur',
    controller: 'Régulateur de charge',
    mounting: 'Structure de montage',
    cabling: 'Kit de câblage',
    total: 'Total estimé',
    addToCart: 'Ajouter au panier →',
    adding: 'Ajout en cours…',
    subtotal: 'Sous-total de la sélection :',
    nextToServices: 'Services et livraison →',

    // Accessoires
    accessoriesTitle: 'Options et accessoires',
    accessoriesDesc: 'Ajoutez des accessoires optionnels à votre configuration (cochez les options souhaitées).',

    // Étape 12 — Services
    servicesTitle: 'Services et livraison',
    servicesBody: 'Indiquez vos préférences pour l\'installation et la récupération du matériel.',
    installerTitle: 'Avez-vous besoin d\'un installateur ?',
    installerYes: 'Oui, je veux un installateur',
    installerYesDesc: 'Notre équipe vous contactera pour planifier l\'installation.',
    installerNo: 'Je fais l\'installation moi-même',
    installerNoDesc: 'Vous bénéficiez de notre support technique gratuit.',
    deliveryTitle: 'Mode de récupération du matériel',
    deliveryHome: 'Livraison à domicile',
    deliveryHomeDesc: 'Livré à votre adresse — frais selon localisation.',
    deliveryPickup: 'Cueillette en magasin',
    deliveryPickupDesc: 'Récupérez votre commande à notre entrepôt.',
    callbackTitle: 'Rappel téléphonique',
    callbackYes: 'Je souhaite être rappelé(e) par l\'équipe Solithium',
    callbackPhone: 'Votre numéro de téléphone',
    callbackPhonePlaceholder: 'ex. 514-555-0100',
    notesTitle: 'Notes additionnelles (optionnel)',
    notesPlaceholder: 'Questions, contraintes particulières, dates souhaitées…',
    summaryTitle: 'Récapitulatif de votre commande',
    finalizeBtn: 'Confirmer et envoyer ma demande →',
    finalizeNote: 'Un courriel de confirmation vous sera envoyé. Aucun paiement n\'est requis à cette étape.',
    sending: 'Envoi en cours…',
  },

  en: {
    // Navigation
    back: 'Back', next: 'Next', step: 'Step',
    calculate: 'Calculate my needs →',
    calculating: 'Calculating…',
    start: 'Get Started',
    day: 'day', days: 'days',

    // Step 1 — Welcome
    welcomeTitle: 'Configure your solar system',
    welcomeBody: 'Answer a few questions and we will recommend the components that fit your needs. The wizard takes about 3 minutes.',

    // Step 2 — Scenario
    scenarioTitle: 'What is your project?',
    scenarioBody: 'Select the situation that best matches your context.',
    scenarioNew: 'New Installation',
    scenarioNewDesc: 'Starting from scratch — no solar equipment currently.',
    scenarioExisting: 'Upgrade / Replacement',
    scenarioExistingDesc: 'You already have an installation and want to replace or add components.',

    // Step 3 — Usage type
    usageTypeTitle: 'Installation type',
    usageResidential: 'Fixed Residential',
    usageResidentialDesc: 'House, cottage, permanent building.',
    usageCommercial: 'Commercial / SMB',
    usageCommercialDesc: 'Office, warehouse, light industrial.',
    usageRv: 'Mobile (RV / Boat)',
    usageRvDesc: 'Recreational vehicle, trailer, sailboat, mobile cabin.',

    // Step 4 — Region
    regionTitle: 'Where will the installation be?',
    regionBody: 'The region determines the average usable peak sun hours (PSH).',
    regionLabel: 'Quebec Region',
    selectRegion: '— Select a region —',
    pshLabel: 'Peak Sun Hours (annual average):',

    // Step 4B — Existing
    existingTitle: 'Describe your current installation',
    existingPanels: 'Existing solar panels',
    existingBatt: 'Existing batteries',
    existingInvCtrl: 'Inverter and charge controller',
    existingInvW: 'Inverter power (W)',
    existingCtrlA: 'Controller amperage (A)',
    qty: 'Quantity',
    wattsEach: 'Watts each',
    battAh: 'Amp-hours (Ah)',
    battVolt: 'Voltage (V)',
    totalPanel: 'Total power:',
    toReplaceTitle: 'Component(s) to replace or add',

    // Step 5 — Periods
    periodsTitle: 'Usage periods',
    periodsMonths: 'Months of use (select all applicable months)',
    dailyHours: 'Estimated daily hours of operation',

    // Step 5B — Existing context
    contextTitle: 'Usage context',
    contextComplaints: 'Issues with current installation (optional)',
    contextPlaceholder: 'E.g.: batteries drain too fast, insufficient panels in winter, inverter overheats…',
    budgetTitle: 'Estimated budget',
    budgetMin: 'Minimum ($)',
    budgetMax: 'Maximum ($)',
    budgetHint: 'Optional — we will filter solutions within your budget range.',

    // Step 6 — Appliances
    appliancesTitle: 'Appliances and energy consumption',
    appliancesBody: 'List the devices you will power. Enter their wattage and estimated daily usage hours.',
    applianceName: 'Appliance name',
    watts: 'Watts',
    hours: 'H/d',
    hDay: 'h/d',
    addAppliance: '+ Add appliance',
    quickAdd: 'Quick add:',
    totalDaily: 'Estimated total daily consumption:',

    // Step 7 — Autonomy
    autonomyTitle: 'Battery autonomy (cloudy days)',
    autonomyBody: 'How many days do you want to operate without sufficient sunlight? Batteries will be sized accordingly.',

    // Step 9 — Area
    areaTitle: 'Available installation area',
    areaBody: 'Available surface to place panels (roof, ground, or RV roof).',
    areaLabel: 'Available area (m²)',

    // Teaser
    teaserTitle: 'Your needs are calculated!',
    teaserDaily: 'Daily consumption',
    teaserPanel: 'Panel capacity',
    teaserBatt: 'Battery storage',
    teaserVolt: 'System voltage',

    // Registration
    gateMessage: 'Create a free account to access recommended products, pricing, and to add your selection to the cart.',
    firstName: 'First name',
    lastName: 'Last name',
    email: 'Email',
    password: 'Password',
    passwordHint: 'Minimum 8 characters',
    privacy: 'Your data is used only for your quote. No unsolicited emails.',
    registerBtn: 'Create account & see solutions →',

    // Solutions
    solutionsTitle: 'Recommended solutions',
    solutionsBody: 'Choose one option per component. The total updates automatically.',
    selectProduct: '— Select —',
    panels: 'Solar panels',
    batteries: 'Batteries',
    inverter: 'Inverter',
    controller: 'Charge controller',
    mounting: 'Mounting structure',
    cabling: 'Wiring kit',
    total: 'Estimated total',
    addToCart: 'Add to cart →',
    adding: 'Adding…',
    subtotal: 'Selection subtotal:',
    nextToServices: 'Services & delivery →',

    // Accessories
    accessoriesTitle: 'Options & accessories',
    accessoriesDesc: 'Add optional accessories to your configuration (check desired options).',

    // Step 12 — Services
    servicesTitle: 'Services & delivery',
    servicesBody: 'Tell us your preferences for installation and product pickup.',
    installerTitle: 'Do you need an installer?',
    installerYes: 'Yes, I need an installer',
    installerYesDesc: 'Our team will contact you to schedule the installation.',
    installerNo: 'I will install it myself',
    installerNoDesc: 'Free technical support included.',
    deliveryTitle: 'How would you like to receive your equipment?',
    deliveryHome: 'Home delivery',
    deliveryHomeDesc: 'Delivered to your address — shipping fees apply.',
    deliveryPickup: 'In-store pickup',
    deliveryPickupDesc: 'Pick up your order at our warehouse.',
    callbackTitle: 'Phone callback',
    callbackYes: 'I would like the Solithium team to call me back',
    callbackPhone: 'Your phone number',
    callbackPhonePlaceholder: 'e.g. 514-555-0100',
    notesTitle: 'Additional notes (optional)',
    notesPlaceholder: 'Questions, special constraints, preferred dates…',
    summaryTitle: 'Your order summary',
    finalizeBtn: 'Confirm and send my request →',
    finalizeNote: 'A confirmation email will be sent to you. No payment is required at this stage.',
    sending: 'Sending…',
  }
};

/* ───────────────────────────────────────────────
   DONNÉES STATIQUES
─────────────────────────────────────────────── */
const SLWIZ_REGIONS = [
  { id: 'montreal',    psh: 3.8, label_fr: 'Montréal / Laval / Rive-Sud',        label_en: 'Montreal / Laval / South Shore' },
  { id: 'quebec',      psh: 3.5, label_fr: 'Québec / Chaudière-Appalaches',       label_en: 'Quebec City / Chaudiere-Appalaches' },
  { id: 'troisriv',    psh: 3.6, label_fr: 'Mauricie / Trois-Rivières',            label_en: 'Mauricie / Trois-Rivières' },
  { id: 'saguenay',    psh: 3.3, label_fr: 'Saguenay–Lac-Saint-Jean',              label_en: 'Saguenay–Lac-Saint-Jean' },
  { id: 'sherbrooke',  psh: 3.8, label_fr: 'Estrie / Sherbrooke',                  label_en: 'Eastern Townships / Sherbrooke' },
  { id: 'rimouski',    psh: 3.2, label_fr: 'Bas-Saint-Laurent / Gaspésie',         label_en: 'Lower Saint Lawrence / Gaspesie' },
  { id: 'abitibi',     psh: 3.1, label_fr: 'Abitibi-Témiscamingue / Outaouais',    label_en: 'Abitibi-Temiscamingue / Outaouais' },
  { id: 'nordquebec',  psh: 2.8, label_fr: 'Nord-du-Québec / Côte-Nord',           label_en: 'Northern Quebec / North Shore' },
  { id: 'laurentides', psh: 3.5, label_fr: 'Laurentides / Lanaudière / Outaouais', label_en: 'Laurentians / Lanaudiere / Outaouais' },
];

const SLWIZ_MONTHS = [
  { id: 1,  fr: 'Jan', en: 'Jan' }, { id: 2,  fr: 'Fév', en: 'Feb' },
  { id: 3,  fr: 'Mar', en: 'Mar' }, { id: 4,  fr: 'Avr', en: 'Apr' },
  { id: 5,  fr: 'Mai', en: 'May' }, { id: 6,  fr: 'Jun', en: 'Jun' },
  { id: 7,  fr: 'Jul', en: 'Jul' }, { id: 8,  fr: 'Aoû', en: 'Aug' },
  { id: 9,  fr: 'Sep', en: 'Sep' }, { id: 10, fr: 'Oct', en: 'Oct' },
  { id: 11, fr: 'Nov', en: 'Nov' }, { id: 12, fr: 'Déc', en: 'Dec' },
];

const SLWIZ_PRESET_APPLIANCES = [
  { name_fr: 'Réfrigérateur',   name_en: 'Refrigerator',     power_w: 150, hours_per_day: 8 },
  { name_fr: 'Ampoule DEL',     name_en: 'LED Bulb',         power_w: 10,  hours_per_day: 5 },
  { name_fr: 'Télévision 40"',  name_en: '40" TV',           power_w: 80,  hours_per_day: 4 },
  { name_fr: 'Ordinateur port.', name_en: 'Laptop',          power_w: 65,  hours_per_day: 6 },
  { name_fr: 'Pompe à eau',     name_en: 'Water pump',       power_w: 350, hours_per_day: 1 },
  { name_fr: 'Chargeur USB',    name_en: 'USB Charger',      power_w: 20,  hours_per_day: 4 },
  { name_fr: 'Micro-ondes',     name_en: 'Microwave',        power_w: 900, hours_per_day: 0.3 },
  { name_fr: 'Ventilateur',     name_en: 'Fan',              power_w: 50,  hours_per_day: 8 },
  { name_fr: 'Chauffe-eau 12V', name_en: '12V Water heater', power_w: 120, hours_per_day: 2 },
];

const SLWIZ_REPLACE_OPTIONS = [
  { id: 'panels',     label_fr: 'Panneaux solaires',  label_en: 'Solar panels' },
  { id: 'batteries',  label_fr: 'Batteries',          label_en: 'Batteries' },
  { id: 'inverter',   label_fr: 'Onduleur',           label_en: 'Inverter' },
  { id: 'controller', label_fr: 'Régulateur MPPT',    label_en: 'MPPT Controller' },
  { id: 'mounting',   label_fr: 'Structure de montage', label_en: 'Mounting structure' },
  { id: 'cabling',    label_fr: 'Câblage',            label_en: 'Cabling' },
];

/* ───────────────────────────────────────────────
   COMPOSANTE ALPINE.JS
─────────────────────────────────────────────── */
function solithiumWizard() {
  return {

    /* ── ÉTAT GLOBAL ───────────────────────── */
    lang:     'fr',
    step:     1,
    loading:  false,

    /* ── SCÉNARIO ──────────────────────────── */
    scenario: null,   // 'new' | 'existing'

    /* ── TYPE D'USAGE ──────────────────────── */
    usageType: null,  // 'residential' | 'commercial' | 'rv'

    /* ── RÉGION ───────────────────────────── */
    regions: SLWIZ_REGIONS,
    region: '',

    /* ── MOIS + HEURES ────────────────────── */
    months: SLWIZ_MONTHS,
    usageMonths: [1,2,3,4,5,6,7,8,9,10,11,12],
    dailyHours: 8,

    /* ── APPAREILS ────────────────────────── */
    appliances: [],
    presetAppliances: SLWIZ_PRESET_APPLIANCES,

    /* ── AUTONOMIE ────────────────────────── */
    autonomyDays: 2,

    /* ── BUDGET ───────────────────────────── */
    budgetMin: null,
    budgetMax: null,

    /* ── SURFACE ──────────────────────────── */
    installArea: 20,

    /* ── INSTALLATION EXISTANTE ───────────── */
    existing: {
      panels_qty: 0, panels_w: 0,
      batt_qty: 0, batt_ah: 0, batt_volts: 12,
      inverter_w: 0, controller_a: 0,
    },
    toReplace: [],
    existingComplaints: '',
    replaceOptions: SLWIZ_REPLACE_OPTIONS,

    /* ── RÉSULTATS SERVEUR ─────────────────── */
    teaser: null,
    needs: null,
    products: null,
    sessionKey: null,

    /* ── INSCRIPTION ──────────────────────── */
    isAuthenticated: false,
    regFirstName: '',
    regLastName: '',
    regEmail: '',
    regPassword: '',
    regError: '',

    /* ── PANIER ───────────────────────────── */
    selectedProducts: {
      panel: '', battery: '', inverter: '', controller: '', mounting: '', cabling: ''
    },
    cartTotal: 0,
    cartError: '',
    cartSuccess: '',
    cartLoading: false,

    /* ── ACCESSOIRES ──────────────────────── */
    accessories: [],
    selectedAccessories: [],

    /* ── SERVICES (étape 12) ──────────────── */
    serviceInstaller: null,   // 'yes' | 'no'
    serviceDelivery:  null,   // 'delivery' | 'pickup'
    serviceCallback:  false,
    servicePhone:     '',
    serviceNotes:     '',

    /* ── TOTAL FINAL ──────────────────────── */
    grandTotal:   0,
    quoteLoading: false,
    quoteError:   '',
    quoteSuccess: '',

    /* ══════════════════════════════════════
       INITIALISATION
    ══════════════════════════════════════ */
    init() {
      // Langue de la page WP (Polylang) en priorité; fallback navigateur.
      const pageLang = (window.slwizParams?.currentLang || '').toLowerCase();
      if (pageLang === 'fr' || pageLang === 'en') {
        this.lang = pageLang;
      } else {
        const browserLang = (navigator.language || 'fr').toLowerCase();
        this.lang = browserLang.startsWith('en') ? 'en' : 'fr';
      }

      // Si l'utilisateur WP est déjà connecté (via slwizParams)
      if (window.slwizParams?.isLoggedIn === 1) {
        this.isAuthenticated = true;
      }

      // Charger les accessoires depuis PHP (slwizParams.accessories)
      if (Array.isArray(window.slwizParams?.accessories)) {
        this.accessories = window.slwizParams.accessories;
      }
    },

    /* ══════════════════════════════════════
       TRADUCTION
    ══════════════════════════════════════ */
    __(key) {
      return SLWIZ_STRINGS[this.lang]?.[key] ?? key;
    },

    /* ══════════════════════════════════════
       NAVIGATION
    ══════════════════════════════════════ */
    maxStep() {
      return 12;  // étape 12 = Services & livraison (pour tous les scénarios)
    },

    progressPct() {
      const total = this.maxStep() - 1;
      const current = this.step - 1;
      return Math.round((current / total) * 100);
    },

    nextStep() {
      if (this.step < this.maxStep()) this.step++;
    },

    prevStep() {
      if (this.step > 1) this.step--;
    },

    /* ══════════════════════════════════════
       HELPERS RÉGION
    ══════════════════════════════════════ */
    selectedRegion() {
      return this.regions.find(r => r.id === this.region) ?? null;
    },

    /* ══════════════════════════════════════
       GESTION DES MOIS
    ══════════════════════════════════════ */
    toggleMonth(id) {
      const idx = this.usageMonths.indexOf(id);
      if (idx > -1) {
        this.usageMonths.splice(idx, 1);
      } else {
        this.usageMonths.push(id);
      }
    },

    /* ══════════════════════════════════════
       GESTION DES APPAREILS
    ══════════════════════════════════════ */
    addAppliance() {
      this.appliances.push({ name: '', power_w: 100, hours_per_day: 4 });
    },

    addPreset(preset) {
      this.appliances.push({
        name: this.lang === 'fr' ? preset.name_fr : preset.name_en,
        power_w: preset.power_w,
        hours_per_day: preset.hours_per_day,
      });
    },

    removeAppliance(idx) {
      this.appliances.splice(idx, 1);
    },

    totalDailyWh() {
      return this.appliances.reduce((sum, a) => sum + (a.power_w || 0) * (a.hours_per_day || 0), 0);
    },

    /* ══════════════════════════════════════
       CALCUL (AJAX vers PHP)
    ══════════════════════════════════════ */
    async triggerCalculate() {
      this.loading = true;
      this.step = 10; // Afficher la page de chargement

      const wizardData = this.scenario === 'new'
        ? {
            region:        this.region,
            usage_type:    this.usageType,
            usage_months:  this.usageMonths,
            daily_hours:   this.dailyHours,
            appliances:    this.appliances,
            autonomy_days: this.autonomyDays,
            budget_min:    this.budgetMin,
            budget_max:    this.budgetMax,
            install_area:  this.installArea,
          }
        : {
            usage_type: this.usageType,
            existing: {
              panels:     [{ qty: this.existing.panels_qty,  watts: this.existing.panels_w }],
              batteries:  [{ qty: this.existing.batt_qty,    ah: this.existing.batt_ah, volts: this.existing.batt_volts }],
              inverter:   { watts: this.existing.inverter_w },
              controller: { amps: this.existing.controller_a },
            },
            to_replace:  this.toReplace,
            complaints:  this.existingComplaints,
            budget_min:  this.budgetMin,
            budget_max:  this.budgetMax,
          };

      try {
        const res = await this._post({
          action:      'slwiz_calculate',
          scenario:    this.scenario,
          wizard_data: JSON.stringify(wizardData),
        });

        if (res.success) {
          this.teaser     = res.data.teaser ?? null;
          this.sessionKey = res.data.session_key ?? null;

          if (res.data.authenticated) {
            this.needs    = res.data.needs;
            this.products = res.data.products;
            this.isAuthenticated = true;
            this.step = 11;
          } else {
            this.step = 10;
          }
        } else {
          alert(res.data?.message ?? 'Erreur / Error');
          this.step = this.scenario === 'new' ? 9 : 5;
        }
      } catch (e) {
        console.error(e);
        alert('Erreur réseau / Network error.');
        this.step = this.scenario === 'new' ? 9 : 5;
      } finally {
        this.loading = false;
      }
    },

    /* ══════════════════════════════════════
       INSCRIPTION / REGISTRATION
    ══════════════════════════════════════ */
    async doRegister() {
      this.loading = true;
      this.regError = '';

      try {
        const res = await this._post({
          action:      'slwiz_register',
          first_name:  this.regFirstName,
          last_name:   this.regLastName,
          email:       this.regEmail,
          password:    this.regPassword,
          session_key: this.sessionKey,
          lang:        this.lang,
        });

        if (res.success) {
          this.needs           = res.data.needs;
          this.products        = res.data.products;
          this.isAuthenticated = true;
          this.step            = 11;
        } else {
          this.regError = res.data?.message ?? (this.lang === 'fr' ? 'Erreur lors de l\'inscription.' : 'Registration error.');
        }
      } catch (e) {
        this.regError = this.lang === 'fr' ? 'Erreur réseau. Réessayez.' : 'Network error. Please retry.';
      } finally {
        this.loading = false;
      }
    },

    /* ══════════════════════════════════════
       PANIER / CART
    ══════════════════════════════════════ */
    updateTotal() {
      let total = 0;
      const prods = this.selectedProducts;

      const addLine = (raw) => {
        if (!raw) return 0;
        try {
          const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
          return p.total_price ?? p.price ?? 0;
        } catch { return 0; }
      };

      total += addLine(prods.panel);
      total += addLine(prods.battery);
      total += addLine(prods.inverter);
      total += addLine(prods.controller);
      total += addLine(prods.mounting);
      total += addLine(prods.cabling);

      this.cartTotal = Math.round(total * 100) / 100;

      // Grand total = composantes + accessoires sélectionnés
      let accTotal = 0;
      this.selectedAccessories.forEach(id => {
        const acc = this.accessories.find(a => a.id === id);
        if (acc) accTotal += acc.price ?? 0;
      });
      this.grandTotal = Math.round((this.cartTotal + accTotal) * 100) / 100;
    },

    cartLines() {
      const lines = [];

      Object.entries(this.selectedProducts).forEach(([key, raw]) => {
        if (!raw) return;
        try {
          const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
          lines.push({
            name:  this.lang === 'fr' ? p.name_fr : p.name_en,
            qty:   p.qty_needed ?? 1,
            total: p.total_price ?? p.price ?? 0,
          });
        } catch {}
      });
      return lines;
    },

    selectedComponentItems() {
      const items = [];

      Object.values(this.selectedProducts).forEach((raw) => {
        if (!raw) return;
        try {
          const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
          const qty = p.qty_needed ?? 1;
          const lineTotal = p.total_price ?? p.price ?? 0;
          const unitPrice = Math.round((lineTotal / (qty || 1)) * 100) / 100;

          items.push({
            name:          this.lang === 'fr' ? p.name_fr : p.name_en,
            qty,
            price:         unitPrice,
            sku:           p.sku ?? '',
            wc_product_id: p.wc_product_id ?? 0,
            wc_variation_id: p.wc_variation_id ?? 0,
            wc_variation_attrs: p.wc_variation_attrs ?? {},
          });
        } catch {}
      });

      return items;
    },

    selectedAccessoryItems() {
      const items = [];
      this.selectedAccessories.forEach(id => {
        const acc = this.accessories.find(a => a.id === id);
        if (!acc) return;
        items.push({
          name:          this.lang === 'fr' ? acc.name_fr : acc.name_en,
          qty:           1,
          price:         acc.price ?? 0,
          sku:           acc.sku ?? '',
          wc_product_id: acc.wc_product_id ?? 0,
        });
      });
      return items;
    },

    goToStep(step) {
      this.step = step;
      requestAnimationFrame(() => {
        const root = document.getElementById('solithium-wizard');
        if (root) root.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    },

    async doAddToCart() {
      this.cartLoading = true;
      this.cartError   = '';
      this.cartSuccess = '';

      const items = [
        ...this.selectedComponentItems(),
        ...this.selectedAccessoryItems(),
      ];

      try {
        const res = await this._post({
          action: 'slwiz_add_to_cart',
          items:  JSON.stringify(items),
          lang:   this.lang,
        });

        if (res.success) {
          if (res.data.demo) {
            this.cartSuccess = `<strong>${res.data.message}</strong><br>`
              + `${this.lang === 'fr' ? 'Total simulé :' : 'Simulated total:'} <strong>${this.formatPrice(res.data.total)}</strong>`;
          } else {
            this.cartSuccess = `${this.lang === 'fr' ? 'Produits ajoutés !' : 'Products added!'} `
              + `<a href="${res.data.cart_url}">${this.lang === 'fr' ? 'Voir le panier →' : 'View cart →'}</a>`;
          }
        } else {
          this.cartError = res.data?.message ?? (this.lang === 'fr' ? 'Erreur.' : 'Error.');
        }
      } catch (e) {
        this.cartError = this.lang === 'fr' ? 'Erreur réseau.' : 'Network error.';
      } finally {
        this.cartLoading = false;
      }
    },

    /* ── Toutes les lignes (composantes + accessoires) ── */
    allCartLines() {
      const lines = [...this.cartLines()];

      this.selectedAccessories.forEach(id => {
        const acc = this.accessories.find(a => a.id === id);
        if (!acc) return;
        lines.push({
          name:  this.lang === 'fr' ? acc.name_fr : acc.name_en,
          qty:   1,
          total: acc.price ?? 0,
        });
      });
      return lines;
    },

    /* ══════════════════════════════════════
       FINALISATION DU DEVIS (étape 12)
    ══════════════════════════════════════ */
    async doFinalizeQuote() {
      this.quoteLoading = true;
      this.quoteError   = '';
      this.quoteSuccess = '';

      // Construire le payload de la commande
      const lines = this.allCartLines();
      const items = this.selectedComponentItems();
      const accessories = this.selectedAccessoryItems();
      const services = {
        installer: this.serviceInstaller ?? '',
        delivery:  this.serviceDelivery  ?? '',
        callback:  this.serviceCallback  ? 1 : 0,
        phone:     this.servicePhone,
        notes:     this.serviceNotes,
      };
      const payload = {
        action:            'slwiz_send_quote',
        lang:              this.lang,
        session_key:       this.sessionKey ?? '',
        items:             JSON.stringify(items),
        accessories:       JSON.stringify(accessories),
        lines:             JSON.stringify(lines),
        services:          JSON.stringify(services),
        grand_total:       this.grandTotal,
        client_email:      this.regEmail,
        client_name:       `${this.regFirstName} ${this.regLastName}`.trim(),
      };

      try {
        const res = await this._post(payload);

        if (res.success) {
          // Ajouter au panier WooCommerce (silencieusement)
          if (lines.length > 0) {
            await this._post({
              action: 'slwiz_add_to_cart',
              items:  JSON.stringify([...items, ...accessories]),
              lang:   this.lang,
            }).catch((err) => { console.warn('add_to_cart failed after quote', err); }); // Non bloquant
          }

          const baseMsg = res.data?.message
            ?? (this.lang === 'fr'
              ? 'Votre demande a été envoyée ! Nous vous contacterons sous peu.'
              : 'Your request has been sent! We will contact you shortly.');

          if (res.data?.order_id) {
            const orderMsg = this.lang === 'fr'
              ? `Commande WooCommerce créée : <strong>#${res.data.order_id}</strong>.`
              : `WooCommerce order created: <strong>#${res.data.order_id}</strong>.`;
            const accountLabel = this.lang === 'fr' ? 'Voir mon compte' : 'View my account';
            const accountPath = this.lang === 'fr' ? '/mon-compte/' : '/my-account/';
            this.quoteSuccess = `${baseMsg}<br>${orderMsg} <a href="${window.slwizParams?.siteUrl ?? ''}${accountPath}">${accountLabel} →</a>`;
          } else {
            this.quoteSuccess = baseMsg;
          }
        } else {
          this.quoteError = res.data?.message
            ?? (this.lang === 'fr' ? 'Erreur lors de l\'envoi.' : 'Error while sending.');
        }
      } catch (e) {
        console.error(e);
        this.quoteError = this.lang === 'fr' ? 'Erreur réseau. Réessayez.' : 'Network error. Please retry.';
      } finally {
        this.quoteLoading = false;
      }
    },

    /* ══════════════════════════════════════
       AFFICHAGE DES SPÉCIFICATIONS
    ══════════════════════════════════════ */
    renderSpecs(raw, type) {
      if (!raw) return '';
      try {
        const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
        if (!p.specs) return '';
        let html = '<dl class="slwiz-specs">';
        Object.entries(p.specs).forEach(([k, v]) => {
          html += `<dt>${this._esc(k)}</dt><dd>${this._esc(v)}</dd>`;
        });
        html += '</dl>';
        if (p.qty_needed > 1) {
          html += `<p class="slwiz-spec-note">${this.lang === 'fr'
            ? `Quantité requise : <strong>${p.qty_needed} unités</strong> · Surface totale : <strong>${p.total_area_m2 ?? '—'} m²</strong>`
            : `Required quantity: <strong>${p.qty_needed} units</strong> · Total area: <strong>${p.total_area_m2 ?? '—'} m²</strong>`}</p>`;
        }
        return html;
      } catch { return ''; }
    },

    /* ══════════════════════════════════════
       UTILITAIRES
    ══════════════════════════════════════ */
    formatPrice(amount) {
      const sym = window.slwizParams?.currency ?? '$';
      return sym + Number(amount).toLocaleString('fr-CA', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    _esc(str) {
      return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    },

    async _post(data) {
      const body = new URLSearchParams({
        nonce: window.slwizParams?.nonce ?? '',
        ...data,
      });
      const resp = await fetch(window.slwizParams?.ajaxUrl ?? '/wp-admin/admin-ajax.php', {
        method:      'POST',
        credentials: 'same-origin',
        headers:     { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body:        body.toString(),
      });
      if (!resp.ok) throw new Error('HTTP ' + resp.status);
      return resp.json();
    },
  };
}

/* ── Enregistrement de la composante Alpine ── */
document.addEventListener('alpine:init', () => {
  Alpine.data('solithiumWizard', solithiumWizard);
});
