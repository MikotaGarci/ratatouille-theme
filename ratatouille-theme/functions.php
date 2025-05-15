<?php
/**
 * Функції теми "Рататуй"
 */

if (!defined('ABSPATH')) {
    exit; // Заборона прямого доступу
}

/**
 * Основні налаштування теми
 */
function ratatouille_setup() {
    // Підтримка перекладу
    load_theme_textdomain('ratatouille', get_template_directory() . '/languages');
    
    // Автоматичний тег <title>
    add_theme_support('title-tag');
    
    // Підтримка мініатюр
    add_theme_support('post-thumbnails');
    
    // Реєстрація меню
    register_nav_menus(array(
        'primary' => __('Головне меню', 'ratatouille'),
        'footer' => __('Меню у підвалі', 'ratatouille')
    ));
    
    // Підтримка HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Логотип
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Підтримка віджетів
    add_theme_support('widgets');
    
    // Додавання розмірів зображень
    add_image_size('dish-thumbnail', 350, 250, true);
}
add_action('after_setup_theme', 'ratatouille_setup');

/**
 * Підключення стилів та скриптів
 */
function ratatouille_scripts() {
    // Google Fonts
    wp_enqueue_style('ratatouille-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;500;600&display=swap');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    
    // Основний стиль
    wp_enqueue_style('ratatouille-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    
    // Додаткові стилі
    wp_enqueue_style('ratatouille-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0');
    
    // Основний JavaScript
    wp_enqueue_script('ratatouille-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0', true);
    
    // Локалізація для скриптів
    wp_localize_script('ratatouille-main-js', 'ratatouille_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'ratatouille_scripts');

/**
 * Стилі та скрипти для галереї
 */
function ratatouille_gallery_scripts() {
    if (is_page_template('templates/gallery.php')) {
        wp_enqueue_style('ratatouille-gallery', get_template_directory_uri() . '/assets/css/gallery.css', array(), '1.0.0');
        wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
        wp_enqueue_script('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
        wp_enqueue_style('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array(), '1.1.0');
        wp_enqueue_script('ratatouille-gallery', get_template_directory_uri() . '/assets/js/gallery.js', array('jquery', 'isotope', 'magnific-popup'), '1.0.0', true);
        
        // Додамо функцію rewind_rows() для підтримки старого коду
        if (!function_exists('rewind_rows')) {
            function rewind_rows() {
                if (function_exists('reset_rows')) {
                    reset_rows(); // Для ACF
                } elseif (function_exists('rewind_posts')) {
                    rewind_posts(); // Для стандартного циклу WordPress
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'ratatouille_gallery_scripts');

/**
 * Реєстрація віджетів
 */
function ratatouille_widgets_init() {
    // Бічна колонка
    register_sidebar(array(
        'name'          => __('Бічна колонка', 'ratatouille'),
        'id'            => 'sidebar-1',
        'description'   => __('Додайте віджети сюди, щоб вони з\'явилися у бічній колонці.', 'ratatouille'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Футер - Колонка 1
    register_sidebar(array(
        'name'          => __('Футер - Колонка 1', 'ratatouille'),
        'id'            => 'footer-1',
        'description'   => __('Додайте віджети сюди, щоб вони з\'явилися у першій колонці футера.', 'ratatouille'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Футер - Колонка 2
    register_sidebar(array(
        'name'          => __('Футер - Колонка 2', 'ratatouille'),
        'id'            => 'footer-2',
        'description'   => __('Додайте віджети сюди, щоб вони з\'явилися у другій колонці футера.', 'ratatouille'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ratatouille_widgets_init');

/**
 * Підключення додаткових файлів
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/template-functions.php';

/**
 * Підтримка ACF (Advanced Custom Fields)
 */
if (class_exists('ACF')) {
    require get_template_directory() . '/inc/acf-setup.php';
}

/**
 * Додавання класу до пунктів меню
 */
function ratatouille_add_menu_link_class($atts, $item, $args) {
    if ($args->theme_location == 'primary') {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'ratatouille_add_menu_link_class', 1, 3);

/**
 * Реєстрація типу запису "Бронювання"
 */
function register_reservation_post_type() {
    $labels = array(
        'name'               => __('Бронювання', 'ratatouille'),
        'singular_name'      => __('Бронювання', 'ratatouille'),
        'menu_name'          => __('Бронювання', 'ratatouille'),
        'add_new'            => __('Додати нове', 'ratatouille'),
        'add_new_item'       => __('Додати нове бронювання', 'ratatouille'),
        'edit_item'          => __('Редагувати бронювання', 'ratatouille'),
        'new_item'           => __('Нове бронювання', 'ratatouille'),
        'view_item'          => __('Переглянути бронювання', 'ratatouille'),
        'search_items'       => __('Пошук бронювань', 'ratatouille'),
        'not_found'          => __('Бронювань не знайдено', 'ratatouille'),
        'not_found_in_trash' => __('У кошику бронювань не знайдено', 'ratatouille'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title'),
    );

    register_post_type('reservation', $args);
}
add_action('init', 'register_reservation_post_type');

/**
 * Обробка форми бронювання
 */
function handle_reservation_submission() {
    if (!isset($_POST['reservation_nonce']) || !wp_verify_nonce($_POST['reservation_nonce'], 'submit_reservation')) {
        wp_send_json_error(__('Невірний nonce', 'ratatouille'));
        return;
    }

    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);
    $date = sanitize_text_field($_POST['date']);
    $time = sanitize_text_field($_POST['time']);
    $guests = sanitize_text_field($_POST['guests']);

    // Створення запису
    $post_data = array(
        'post_title'  => sprintf(__('Бронювання: %s - %s', 'ratatouille'), $name, $date),
        'post_status' => 'publish',
        'post_type'   => 'reservation'
    );
    function ratatouille_gallery_scripts() {
    if (is_page_template('templates/gallery.php')) {
        // Isotope
        wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
        wp_enqueue_script('imagesloaded', 'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js', array(), '4.1.4', true);
        
        // Magnific Popup
        wp_enqueue_script('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
        wp_enqueue_style('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array(), '1.1.0');
        
        // Стили галереи
        wp_enqueue_style('ratatouille-gallery', get_template_directory_uri() . '/assets/css/gallery.css');
        
        // Скрипт инициализации
        wp_enqueue_script('ratatouille-gallery', get_template_directory_uri() . '/assets/js/gallery.js', array('jquery', 'isotope', 'imagesloaded', 'magnific-popup'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'ratatouille_gallery_scripts');
add_action('after_setup_theme', function() {
    add_image_size('medium_large', 768, 0, false);
});
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Оновлення полів ACF
        update_field('reservation_name', $name, $post_id);
        update_field('reservation_phone', $phone, $post_id);
        update_field('reservation_date', $date, $post_id);
        update_field('reservation_time', $time, $post_id);
        update_field('reservation_guests', $guests, $post_id);
        update_field('reservation_status', 'pending', $post_id);

        // Відправка email-сповіщення адміну
        $to = get_option('admin_email');
        $subject = sprintf(__('Нове бронювання від %s', 'ratatouille'), $name);
        $message = sprintf(
            __("Деталі нового бронювання:\n\nІм'я: %s\nТелефон: %s\nДата: %s\nЧас: %s\nГості: %s", 'ratatouille'),
            $name,
            $phone,
            $date,
            $time,
            $guests
        );
        wp_mail($to, $subject, $message);

        wp_send_json_success(__('Бронювання успішно створено', 'ratatouille'));
    } else {
        wp_send_json_error(__('Помилка при створенні бронювання', 'ratatouille'));
    }
}
add_action('wp_ajax_submit_reservation', 'handle_reservation_submission');
add_action('wp_ajax_nopriv_submit_reservation', 'handle_reservation_submission');