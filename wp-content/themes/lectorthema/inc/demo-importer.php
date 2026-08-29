<?php
/**
 * LectorThema - Importador de Datos Demo Automático
 *
 * Genera obras de demostración para Manga, Manhwa, Manhua y Fan Comics
 * con capítulos, portadas, géneros y estadísticas para visualización inmediata.
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

function lectorthema_auto_seed_demo_data() {
    // Solo ejecutar si no existen obras creadas
    $existing = get_posts([
        'post_type'      => 'manga',
        'posts_per_page' => 1,
        'post_status'    => 'any'
    ]);

    if (!empty($existing)) {
        return; // Ya hay contenido
    }

    $demo_mangas = [
        [
            'title'       => 'El demonio celestial quiere una vida tranquila',
            'type'        => 'manhwa',
            'genres'      => ['artes-marciales', 'accion', 'sobrenatural', 'reencarnacion'],
            'status'      => 'en-emision',
            'author'      => 'Gwang Sam',
            'artist'      => 'Redice Studio',
            'year'        => '2024',
            'rating'      => '9.8',
            'featured'    => '1',
            'synopsis'    => 'Tras un milenio de sangrientas batallas en el mundo Murim, el Demonio Celestial busca reencarnar y vivir una vida pacífica en el mundo moderno, pero oscuras organizaciones no se lo permitirán.',
            'cover_url'   => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '89', 'time' => '-2 hours', 'hot' => true],
                ['num' => '88', 'time' => '-1 week', 'hot' => false],
                ['num' => '87', 'time' => '-2 weeks', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Solo Leveling: Ragnarok',
            'type'        => 'manhwa',
            'genres'      => ['accion', 'fantasia', 'isekai'],
            'status'      => 'en-emision',
            'author'      => 'Chugong / Daul',
            'artist'      => 'Redice Studio',
            'year'        => '2024',
            'rating'      => '9.9',
            'featured'    => '1',
            'synopsis'    => 'La presencia de los Monarcas se ha debilitado, pero nuevas grietas dimensionales emergen en la Tierra. Sung Suho, el heredero del Monarca de las Sombras, despierta su poder para proteger al mundo.',
            'cover_url'   => 'https://images.unsplash.com/photo-1563089145-599997674d42?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '45', 'time' => '-5 hours', 'hot' => true],
                ['num' => '44', 'time' => '-4 days', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Martial Peak (Cúspide Marcial)',
            'type'        => 'manhua',
            'genres'      => ['artes-marciales', 'aventura', 'fantasia'],
            'status'      => 'en-emision',
            'author'      => 'Momo',
            'artist'      => 'Pikapi',
            'year'        => '2023',
            'rating'      => '9.4',
            'featured'    => '0',
            'synopsis'    => 'El viaje hacia la cúspide marcial es solitario y desafiante. Yang Kai, un simple barrendero de la secta Lingxiao, obtiene un libro negro sin palabras que cambiará su destino.',
            'cover_url'   => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '3800', 'time' => '-3 hours', 'hot' => true],
                ['num' => '3799', 'time' => '-1 day', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Jujutsu Kaisen: Heian Chronicles',
            'type'        => 'manga',
            'genres'      => ['accion', 'sobrenatural', 'drama'],
            'status'      => 'en-emision',
            'author'      => 'Gege Akutami',
            'artist'      => 'Gege Akutami',
            'year'        => '2024',
            'rating'      => '9.7',
            'featured'    => '1',
            'synopsis'    => 'La era dorada del hechicería jujutsu en el periodo Heian donde Sukuna reinaba sobre los espíritus malditos y hechiceros legendarios.',
            'cover_url'   => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '271', 'time' => '-1 hour', 'hot' => true],
                ['num' => '270', 'time' => '-6 days', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Dragon Ball: New Multiverse',
            'type'        => 'fan-comic',
            'genres'      => ['accion', 'artes-marciales', 'ciencia-ficcion'],
            'status'      => 'en-emision',
            'author'      => 'Salagir / Gogeta Jr',
            'artist'      => 'Asura Community',
            'year'        => '2024',
            'rating'      => '9.2',
            'featured'    => '0',
            'synopsis'    => 'Un torneo colosal entre infinitos universos alternativos donde diferentes líneas temporales de guerreros Z compiten por el deseo supremo.',
            'cover_url'   => 'https://images.unsplash.com/photo-1563089145-599997674d42?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '15', 'time' => '-8 hours', 'hot' => true],
                ['num' => '14', 'time' => '-2 weeks', 'hot' => false],
            ]
        ],
        [
            'title'       => 'The Beginning After The End',
            'type'        => 'manhwa',
            'genres'      => ['fantasia', 'isekai', 'aventura', 'reencarnacion'],
            'status'      => 'en-emision',
            'author'      => 'TurtleMe',
            'artist'      => 'Fuyuki23',
            'year'        => '2024',
            'rating'      => '9.8',
            'featured'    => '1',
            'synopsis'    => 'El rey Grey posee fuerza y riquezas inigualables, pero la soledad lo consume. Renacido en un mundo mágico lleno de monstruos, tiene una segunda oportunidad para proteger a sus seres queridos.',
            'cover_url'   => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '185', 'time' => '-6 hours', 'hot' => true],
                ['num' => '184', 'time' => '-1 week', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Omniscient Reader (Punto de Vista del Lector)',
            'type'        => 'manhwa',
            'genres'      => ['accion', 'misterio', 'sobrenatural'],
            'status'      => 'en-emision',
            'author'      => 'Sing Shong',
            'artist'      => 'Sleepy-C',
            'year'        => '2024',
            'rating'      => '9.9',
            'featured'    => '0',
            'synopsis'    => 'Kim Dokja era el único lector de una novela web apocalíptica de 3149 capítulos. Cuando el mundo real se transforma exactamente en dicha historia, él es el único que sabe cómo sobrevivir.',
            'cover_url'   => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '220', 'time' => '-4 hours', 'hot' => true],
                ['num' => '219', 'time' => '-5 days', 'hot' => false],
            ]
        ],
        [
            'title'       => 'Nano Machine',
            'type'        => 'manhwa',
            'genres'      => ['artes-marciales', 'ciencia-ficcion', 'accion'],
            'status'      => 'en-emision',
            'author'      => 'Jeolmu Hyeon',
            'artist'      => 'GGBG',
            'year'        => '2024',
            'rating'      => '9.6',
            'featured'    => '0',
            'synopsis'    => 'Cheon Yeo-Woon, un hijo ilegítimo acosado del Culto Demoníaco, recibe la visita de un descendiente del futuro que le inyecta nanomáquinas avanzadas en su cuerpo.',
            'cover_url'   => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=600&auto=format&fit=crop&q=80',
            'banner_url'  => 'https://images.unsplash.com/photo-1563089145-599997674d42?w=1400&auto=format&fit=crop&q=80',
            'chapters'    => [
                ['num' => '210', 'time' => '-12 hours', 'hot' => true],
                ['num' => '209', 'time' => '-1 week', 'hot' => false],
            ]
        ]
    ];

    foreach ($demo_mangas as $item) {
        $post_id = wp_insert_post([
            'post_title'   => $item['title'],
            'post_content' => $item['synopsis'],
            'post_status'  => 'publish',
            'post_type'    => 'manga',
            'post_author'  => 1
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Asignar Taxonomías
            wp_set_object_terms($post_id, $item['type'], 'manga_type');
            wp_set_object_terms($post_id, $item['genres'], 'manga_genre');
            wp_set_object_terms($post_id, $item['status'], 'manga_status');

            // Asignar Metadatos
            update_post_meta($post_id, '_manga_author', $item['author']);
            update_post_meta($post_id, '_manga_artist', $item['artist']);
            update_post_meta($post_id, '_manga_release_year', $item['year']);
            update_post_meta($post_id, '_manga_rating', $item['rating']);
            update_post_meta($post_id, '_manga_banner_url', $item['banner_url']);
            update_post_meta($post_id, '_manga_is_featured', $item['featured']);
            update_post_meta($post_id, '_manga_badge_icon', 'https://cdn-icons-png.flaticon.com/512/1055/1055687.png');
            update_post_meta($post_id, '_manga_custom_cover', $item['cover_url']);

            // Crear Capítulos
            foreach ($item['chapters'] as $ch) {
                $ch_date = date('Y-m-d H:i:s', strtotime($ch['time'], current_time('timestamp')));
                $ch_id = wp_insert_post([
                    'post_title'   => $item['title'] . ' - Capítulo ' . $ch['num'],
                    'post_content' => 'Lector del capítulo ' . $ch['num'],
                    'post_status'  => 'publish',
                    'post_type'    => 'chapter',
                    'post_date'    => $ch_date,
                    'post_author'  => 1
                ]);

                if ($ch_id && !is_wp_error($ch_id)) {
                    update_post_meta($ch_id, '_chapter_manga_id', $post_id);
                    update_post_meta($ch_id, '_chapter_number', $ch['num']);
                    update_post_meta($ch_id, '_chapter_is_hot', $ch['hot'] ? '1' : '0');
                    update_post_meta($ch_id, '_chapter_images', implode("\n", [
                        'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=1000&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1000&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1000&auto=format&fit=crop&q=80'
                    ]));
                }
            }

            // Registrar estadísticas de vistas iniciales
            lectorthema_seed_manga_views($post_id);
        }
    }
}
add_action('after_switch_theme', 'lectorthema_auto_seed_demo_data');
add_action('admin_init', 'lectorthema_auto_seed_demo_data');

function lectorthema_seed_manga_views($manga_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'manga_views';
    $today = current_time('Y-m-d');
    $rand_today = rand(800, 2500);

    $wpdb->query($wpdb->prepare(
        "INSERT INTO $table (manga_id, view_date, views_count) VALUES (%d, %s, %d)
         ON DUPLICATE KEY UPDATE views_count = views_count + %d",
        $manga_id,
        $today,
        $rand_today,
        $rand_today
    ));
}
