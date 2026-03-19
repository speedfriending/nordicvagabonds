<?php
/**
 * Nordic Vagabonds Theme Functions
 */

// ─── ENQUEUE ──────────────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'nv_enqueue' );
function nv_enqueue() {
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,400&family=DM+Sans:wght@300;400;500&display=swap',
        [],
        null
    );
    wp_enqueue_style(
        'nordicvagabonds-style',
        get_stylesheet_uri(),
        ['google-fonts'],
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'nordicvagabonds-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );
}

// ─── THEME SUPPORT ─────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'nv_setup' );
function nv_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_image_size( 'profile-portrait', 480, 640, true );
    add_image_size( 'hero-wide', 1400, 900, true );
}

// ─── NAV MENU ──────────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'nv_menus' );
function nv_menus() {
    register_nav_menus( [
        'primary' => __( 'Primærnavigasjon', 'nordicvagabonds' ),
    ] );
}

// ─── CUSTOM POST TYPE: TEAM ────────────────────────────────────────────────────
add_action( 'init', 'nv_register_cpt_team' );
function nv_register_cpt_team() {
    register_post_type( 'nv_team', [
        'labels' => [
            'name'               => 'Teammedlemmer',
            'singular_name'      => 'Teammedlem',
            'add_new_item'       => 'Legg til teammedlem',
            'edit_item'          => 'Rediger teammedlem',
            'not_found'          => 'Ingen teammedlemmer funnet',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => [ 'title', 'thumbnail' ],
        'menu_position' => 5,
        'show_in_rest' => true,
    ] );
}

// ─── CUSTOM POST TYPE: TJENESTER ───────────────────────────────────────────────
add_action( 'init', 'nv_register_cpt_services' );
function nv_register_cpt_services() {
    register_post_type( 'nv_service', [
        'labels' => [
            'name'               => 'Tjenester',
            'singular_name'      => 'Tjeneste',
            'add_new_item'       => 'Legg til tjeneste',
            'edit_item'          => 'Rediger tjeneste',
            'not_found'          => 'Ingen tjenester funnet',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => [ 'title' ],
        'menu_position' => 6,
        'show_in_rest' => true,
    ] );
}

// ─── ACF FIELD GROUPS ─────────────────────────────────────────────────────────
// ACF-feltene er definert i /acf-json/ og lastes automatisk.
// Legg til Local JSON-støtte:
add_filter( 'acf/settings/save_json', 'nv_acf_json_save' );
function nv_acf_json_save( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/load_json', 'nv_acf_json_load' );
function nv_acf_json_load( $paths ) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}

// ─── FALLBACK: Programmatisk ACF-registrering ──────────────────────────────────
// Kjøres hvis acf-json-filene ikke er importert ennå
add_action( 'acf/init', 'nv_register_acf_fields' );
function nv_register_acf_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) return;

    // ── Frontpage Options ──────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_nv_frontpage',
        'title'    => 'Forsideinnhold',
        'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
        'fields'   => [
            // Hero
            [ 'key'=>'field_hero_tag',   'label'=>'Hero — liten tekst over tittel', 'name'=>'hero_tag',   'type'=>'text',     'default_value'=>'Konsulent & forretningsutvikling' ],
            [ 'key'=>'field_hero_title', 'label'=>'Hero — tittel (HTML ok, bruk <em> for kursiv)', 'name'=>'hero_title', 'type'=>'textarea', 'rows'=>3, 'default_value'=>"Vi bygger\n<em>det som\nbetyr noe.</em>" ],
            [ 'key'=>'field_hero_body',  'label'=>'Hero — brødtekst', 'name'=>'hero_body',  'type'=>'textarea', 'rows'=>3, 'default_value'=>'Et skandinavisk byrå med bred kompetanse — jurister, økonomer, markedsførere og psykologer som jobber mot ett mål.' ],
            [ 'key'=>'field_hero_cta',   'label'=>'Hero — knappetekst', 'name'=>'hero_cta',   'type'=>'text',     'default_value'=>'Ta kontakt' ],
            [ 'key'=>'field_hero_img',   'label'=>'Hero — bilde (høyre side)', 'name'=>'hero_img',   'type'=>'image',    'return_format'=>'array', 'preview_size'=>'medium' ],
            // About
            [ 'key'=>'field_about_heading', 'label'=>'Om oss — overskrift', 'name'=>'about_heading', 'type'=>'textarea', 'rows'=>2, 'default_value'=>"Tverrfaglig lag.\n<em>Felles retning.</em>" ],
            [ 'key'=>'field_about_text1',   'label'=>'Om oss — avsnitt 1', 'name'=>'about_text1',   'type'=>'textarea', 'rows'=>3 ],
            [ 'key'=>'field_about_text2',   'label'=>'Om oss — avsnitt 2', 'name'=>'about_text2',   'type'=>'textarea', 'rows'=>3 ],
            [ 'key'=>'field_about_img',     'label'=>'Om oss — bilde', 'name'=>'about_img',     'type'=>'image',    'return_format'=>'array', 'preview_size'=>'medium' ],
            [ 'key'=>'field_about_tags',    'label'=>'Om oss — fagområder (ett per linje)', 'name'=>'about_tags',    'type'=>'textarea', 'rows'=>4, 'default_value'=>"Jus\nØkonomi\nPsykologi\nHelse\nMarkedsføring\nForskning\nProsjektledelse" ],
            // Services heading
            [ 'key'=>'field_services_intro', 'label'=>'Tjenester — ingress', 'name'=>'services_intro', 'type'=>'text', 'default_value'=>'Vi tilpasser oss oppdraget — og ikke omvendt.' ],
            // Team heading
            [ 'key'=>'field_team_heading', 'label'=>'Team — overskrift', 'name'=>'team_heading', 'type'=>'text', 'default_value'=>'Folk som brenner for det.' ],
            // Press
            [ 'key'=>'field_press_logos', 'label'=>'Omtalt i — mediernavn (ett per linje)', 'name'=>'press_logos', 'type'=>'textarea', 'rows'=>4, 'default_value'=>"NRK\nVG\nAftenposten\nGod Morgen Norge\nDagsavisen\nStavanger Aftenblad" ],
            // Contact
            [ 'key'=>'field_contact_heading', 'label'=>'Kontakt — overskrift', 'name'=>'contact_heading', 'type'=>'textarea', 'rows'=>2, 'default_value'=>"La oss ta\n<em>en kaffe.</em>" ],
            [ 'key'=>'field_contact_email',   'label'=>'Kontakt — e-postadresse', 'name'=>'contact_email',   'type'=>'email',    'default_value'=>'hei@nordicvagabonds.no' ],
            [ 'key'=>'field_contact_address', 'label'=>'Kontakt — adresse', 'name'=>'contact_address', 'type'=>'textarea', 'rows'=>2, 'default_value'=>"Leirvågvegen 193\n6390 Vestnes" ],
            [ 'key'=>'field_contact_projects','label'=>'Kontakt — tilknyttede prosjekter', 'name'=>'contact_projects','type'=>'textarea','rows'=>2, 'default_value'=>"speedfriending.no\nspeedfriending.com" ],
            [ 'key'=>'field_contact_orgnr',   'label'=>'Kontakt — org.nr.', 'name'=>'contact_orgnr',   'type'=>'text',     'default_value'=>'920 812 848' ],
            [ 'key'=>'field_contact_form_id', 'label'=>'Kontakt — CF7 skjema-ID', 'name'=>'contact_form_id', 'type'=>'number', 'instructions'=>'Finn ID-en til Contact Form 7-skjemaet ditt' ],
        ],
    ]);

    // ── Team Member Fields ─────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_nv_team',
        'title'    => 'Teammedlem',
        'location' => [[[ 'param' => 'post_type', 'operator' => '==', 'value' => 'nv_team' ]]],
        'fields'   => [
            [ 'key'=>'field_team_role',  'label'=>'Rolle / tittel', 'name'=>'team_role',  'type'=>'text' ],
            [ 'key'=>'field_team_photo', 'label'=>'Profilbilde',    'name'=>'team_photo', 'type'=>'image', 'return_format'=>'array', 'preview_size'=>'thumbnail' ],
            [ 'key'=>'field_team_order', 'label'=>'Rekkefølge (lavest vises først)', 'name'=>'team_order', 'type'=>'number', 'default_value'=>10 ],
        ],
    ]);

    // ── Service Fields ─────────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_nv_service',
        'title'    => 'Tjeneste',
        'location' => [[[ 'param' => 'post_type', 'operator' => '==', 'value' => 'nv_service' ]]],
        'fields'   => [
            [ 'key'=>'field_service_desc',  'label'=>'Beskrivelse', 'name'=>'service_desc',  'type'=>'textarea', 'rows'=>3 ],
            [ 'key'=>'field_service_order', 'label'=>'Rekkefølge',  'name'=>'service_order', 'type'=>'number',   'default_value'=>10 ],
        ],
    ]);
}
