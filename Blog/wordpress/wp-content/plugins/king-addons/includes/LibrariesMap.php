<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class LibrariesMap
{
    public static function getLibrariesMapArray(): array
    {
        return [
            'libraries' => [
                'animation' => [
                    'css' => ['general', 'button', 'timing', 'loading'],
                    'js' => []
                ],
                'aos' => [
                    'css' => [],
                    'js' => ['aos']
                ],
                'charts' => [
                    'css' => [],
                    'js' => ['charts']
                ],
                'flipclock' => [
                    'css' => ['flipclock'],
                    'js' => ['flipclock']
                ],
                'fullpage' => [
                    'css' => [],
                    'js' => ['fullpage']
                ],
                'general' => [
                    'css' => ['general'],
                    'js' => []
                ],
                'grid' => [
                    'css' => ['grid'],
                    'js' => [
                        'grid',
                        'media',
                        'woocommerce',
//                        'prev'
                    ]
                ],
                'imagesloaded' => [
                    'css' => [],
                    'js' => ['imagesloaded']
                ],
                'infinitescroll' => [
                    'css' => [],
                    'js' => ['infinitescroll']
                ],
                'isotope' => [
                    'css' => [],
                    'js' => ['isotope', 'kng']
                ],
                'jarallax' => [
                    'css' => [],
                    'js' => ['jarallax']
                ],
                'jquery' => [
                    'css' => [],
                    'js' => ['jquery']
                ],
                'jqueryeventmove' => [
                    'css' => [],
                    'js' => ['jqueryeventmove']
                ],
                'jquerynumerator' => [
                    'css' => [],
                    'js' => ['jquerynumerator']
                ],
                'lightgallery' => [
                    'css' => ['lightgallery'],
                    'js' => ['lightgallery']
                ],
                'lottie' => [
                    'css' => [],
                    'js' => ['lottie']
                ],
                'macy' => [
                    'css' => [],
                    'js' => ['macy']
                ],
                'markerclusterer' => [
                    'css' => [],
                    'js' => ['markerclusterer']
                ],
                'marquee' => [
                    'css' => [],
                    'js' => ['marquee']
                ],
                'odometer' => [
                    'css' => ['minimal'],
                    'js' => ['odometer']
                ],
                'parallax' => [
                    'css' => [],
                    'js' => ['parallax']
                ],
                'particles' => [
                    'css' => [],
                    'js' => ['particles']
                ],
                'perfectscrollbar' => [
                    'css' => [],
                    'js' => ['perfectscrollbar']
                ],
                'slick' => [
                    'css' => ['helper'],
                    'js' => ['slick']
                ],
                'swiper' => [
                    'css' => ['swiper'],
                    'js' => ['swiper']
                ],
                'tabletoexcel' => [
                    'css' => [],
                    'js' => ['tabletoexcel']
                ],
                'wpcolorpicker' => [
                    'css' => [],
                    'js' => ['wpcolorpicker']
                ],
            ]
        ];
    }
}