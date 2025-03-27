<?php

namespace King_Addons\Button_Animations;

use Elementor\Base_Data_Control;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Button_Animations extends Base_Data_Control
{

    private static ?array $_animations = null;

    public function get_type(): string
    {
        return 'king-addons-button-animations';
    }

    public static function get_animations(): array
    {
        // Return early if already set
        if (null !== self::$_animations) {
            return self::$_animations;
        }

        $is_premium = king_addons_freemius()->can_use_premium_code__premium_only();

        $pro_placeholders = [
            'pro-king-addons-button-berlin' => esc_html__('Berlin + Text (Pro)', 'king-addons'),
            'pro-king-addons-button-madrid-left' => esc_html__('Madrid Left + Text (Pro)', 'king-addons'),
            'pro-king-addons-button-madrid-right' => esc_html__('Madrid Right + Text (Pro)', 'king-addons'),
            'pro-king-addons-button-london-left' => esc_html__('London Left (Pro)', 'king-addons'),
            'pro-king-addons-button-rome-left' => esc_html__('Rome Left (Pro)', 'king-addons'),

        ];
        $pro_real = [
            'king-addons-button-berlin' => esc_html__('Berlin + Text', 'king-addons'),
            'king-addons-button-madrid-left' => esc_html__('Madrid Left + Text', 'king-addons'),
            'king-addons-button-madrid-right' => esc_html__('Madrid Right + Text', 'king-addons'),
            'king-addons-button-london-left' => esc_html__('London Left', 'king-addons'),
            'king-addons-button-rome-left' => esc_html__('Rome Left', 'king-addons'),
        ];

        $animation_pro_or_free = $is_premium ? $pro_real : $pro_placeholders;

        // Animations
        $animations_group = array_merge(
            $animation_pro_or_free,
            [
                'king-addons-button-london-right' => esc_html__('London Right', 'king-addons'),
                'king-addons-button-rome-right' => esc_html__('Rome Right', 'king-addons'),
                'king-addons-button-paris' => esc_html__('Paris', 'king-addons'),
                'king-addons-button-oslo' => esc_html__('Oslo', 'king-addons'),
            ]
        );

        // 2D Animations
        $animations_2d = [
            'elementor-animation-backward' => esc_html__('Backward', 'king-addons'),
            'elementor-animation-bob' => esc_html__('Bob', 'king-addons'),
            'elementor-animation-bounce-in' => esc_html__('Bounce In', 'king-addons'),
            'elementor-animation-bounce-out' => esc_html__('Bounce Out', 'king-addons'),
            'elementor-animation-buzz' => esc_html__('Buzz', 'king-addons'),
            'elementor-animation-buzz-out' => esc_html__('Buzz Out', 'king-addons'),
            'elementor-animation-float' => esc_html__('Float', 'king-addons'),
            'elementor-animation-forward' => esc_html__('Forward', 'king-addons'),
            'elementor-animation-grow' => esc_html__('Grow', 'king-addons'),
            'elementor-animation-grow-rotate' => esc_html__('Grow Rotate', 'king-addons'),
            'elementor-animation-hang' => esc_html__('Hang', 'king-addons'),
            'elementor-animation-pop' => esc_html__('Pop', 'king-addons'),
            'elementor-animation-pulse' => esc_html__('Pulse', 'king-addons'),
            'elementor-animation-pulse-grow' => esc_html__('Pulse Grow', 'king-addons'),
            'elementor-animation-pulse-shrink' => esc_html__('Pulse Shrink', 'king-addons'),
            'elementor-animation-push' => esc_html__('Push', 'king-addons'),
            'elementor-animation-rotate' => esc_html__('Rotate', 'king-addons'),
            'elementor-animation-shrink' => esc_html__('Shrink', 'king-addons'),
            'elementor-animation-sink' => esc_html__('Sink', 'king-addons'),
            'elementor-animation-skew' => esc_html__('Skew', 'king-addons'),
            'elementor-animation-skew-backward' => esc_html__('Skew Backward', 'king-addons'),
            'elementor-animation-skew-forward' => esc_html__('Skew Forward', 'king-addons'),
            'elementor-animation-wobble-bottom' => esc_html__('Wobble Bottom', 'king-addons'),
            'elementor-animation-wobble-horizontal' => esc_html__('Wobble Horizontal', 'king-addons'),
            'elementor-animation-wobble-skew' => esc_html__('Wobble Skew', 'king-addons'),
            'elementor-animation-wobble-to-bottom-right' => esc_html__('Wobble To Bottom Right', 'king-addons'),
            'elementor-animation-wobble-to-top-right' => esc_html__('Wobble To Top Right', 'king-addons'),
            'elementor-animation-wobble-top' => esc_html__('Wobble Top', 'king-addons'),
            'elementor-animation-wobble-vertical' => esc_html__('Wobble Vertical', 'king-addons'),
        ];

        // Background Animations
        $pro_real_animations_BG = [
            'king-addons-button-none' => esc_html__('None', 'king-addons'),
            'king-addons-button-bounce-to-bottom' => esc_html__('Bounce To Bottom', 'king-addons'),
            'king-addons-button-bounce-to-left' => esc_html__('Bounce To Left', 'king-addons'),
            'king-addons-button-bounce-to-right' => esc_html__('Bounce To Right', 'king-addons'),
            'king-addons-button-bounce-to-top' => esc_html__('Bounce To Top', 'king-addons'),
            'king-addons-button-overline-from-center' => esc_html__('Overline From Center', 'king-addons'),
            'king-addons-button-overline-from-left' => esc_html__('Overline From Left', 'king-addons'),
            'king-addons-button-overline-from-right' => esc_html__('Overline From Right', 'king-addons'),
            'king-addons-button-overline-reveal' => esc_html__('Overline Reveal', 'king-addons'),
            'king-addons-button-radial-in' => esc_html__('Radial In', 'king-addons'),
            'king-addons-button-radial-out' => esc_html__('Radial Out', 'king-addons'),
            'king-addons-button-rectangle-in' => esc_html__('Rectangle In', 'king-addons'),
            'king-addons-button-rectangle-out' => esc_html__('Rectangle Out', 'king-addons'),
            'king-addons-button-shutter-in-horizontal' => esc_html__('Shutter In Horizontal', 'king-addons'),
            'king-addons-button-shutter-in-vertical' => esc_html__('Shutter In Vertical', 'king-addons'),
            'king-addons-button-shutter-out-horizontal' => esc_html__('Shutter Out Horizontal', 'king-addons'),
            'king-addons-button-shutter-out-vertical' => esc_html__('Shutter Out Vertical', 'king-addons'),
            'king-addons-button-sweep-to-bottom' => esc_html__('Sweep To Bottom', 'king-addons'),
            'king-addons-button-sweep-to-left' => esc_html__('Sweep To Left', 'king-addons'),
            'king-addons-button-sweep-to-right' => esc_html__('Sweep To Right', 'king-addons'),
            'king-addons-button-sweep-to-top' => esc_html__('Sweep To top', 'king-addons'),
            'king-addons-button-underline-from-center' => esc_html__('Underline From Center', 'king-addons'),
            'king-addons-button-underline-from-left' => esc_html__('Underline From Left', 'king-addons'),
            'king-addons-button-underline-from-right' => esc_html__('Underline From Right', 'king-addons'),
            'king-addons-button-underline-reveal' => esc_html__('Underline Reveal', 'king-addons'),
        ];

        $pro_placeholders_animations_BG = [
            'king-addons-button-none' => esc_html__('None', 'king-addons'),
            'king-addons-button-bounce-to-bottom' => esc_html__('Bounce To Bottom', 'king-addons'),
            'pro-king-addons-button-bounce-to-left' => esc_html__('Bounce To Left (Pro)', 'king-addons'),
            'pro-king-addons-button-bounce-to-right' => esc_html__('Bounce To Right (Pro)', 'king-addons'),
            'pro-king-addons-button-bounce-to-top' => esc_html__('Bounce To Top (Pro)', 'king-addons'),
            'king-addons-button-overline-from-center' => esc_html__('Overline From Center', 'king-addons'),
            'pro-king-addons-button-overline-from-left' => esc_html__('Overline From Left (Pro)', 'king-addons'),
            'pro-king-addons-button-overline-from-right' => esc_html__('Overline From Right (Pro)', 'king-addons'),
            'pro-king-addons-button-overline-reveal' => esc_html__('Overline Reveal (Pro)', 'king-addons'),
            'king-addons-button-radial-in' => esc_html__('Radial In', 'king-addons'),
            'pro-king-addons-button-radial-out' => esc_html__('Radial Out (Pro)', 'king-addons'),
            'king-addons-button-rectangle-in' => esc_html__('Rectangle In', 'king-addons'),
            'pro-king-addons-button-rectangle-out' => esc_html__('Rectangle Out (Pro)', 'king-addons'),
            'king-addons-button-shutter-in-horizontal' => esc_html__('Shutter In Horizontal', 'king-addons'),
            'pro-king-addons-button-shutter-in-vertical' => esc_html__('Shutter In Vertical (Pro)', 'king-addons'),
            'pro-king-addons-button-shutter-out-horizontal' => esc_html__('Shutter Out Horizontal (Pro)', 'king-addons'),
            'pro-king-addons-button-shutter-out-vertical' => esc_html__('Shutter Out Vertical (Pro)', 'king-addons'),
            'pro-king-addons-button-sweep-to-bottom' => esc_html__('Sweep To Bottom (Pro)', 'king-addons'),
            'pro-king-addons-button-sweep-to-left' => esc_html__('Sweep To Left (Pro)', 'king-addons'),
            'pro-king-addons-button-sweep-to-right' => esc_html__('Sweep To Right (Pro)', 'king-addons'),
            'king-addons-button-sweep-to-top' => esc_html__('Sweep To top', 'king-addons'),
            'king-addons-button-underline-from-center' => esc_html__('Underline From Center', 'king-addons'),
            'pro-king-addons-button-underline-from-left' => esc_html__('Underline From Left (Pro)', 'king-addons'),
            'pro-king-addons-button-underline-from-right' => esc_html__('Underline From Right (Pro)', 'king-addons'),
            'pro-king-addons-button-underline-reveal' => esc_html__('Underline Reveal (Pro)', 'king-addons'),
        ];

        $animations_bg = $is_premium ? $pro_real_animations_BG : $pro_placeholders_animations_BG;

        self::$_animations = [
            'Background Animations' => $animations_bg,
            'Animations' => $animations_group,
            '2D Animations' => $animations_2d,
        ];

        return self::$_animations;
    }

    public function content_template(): void
    {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">
                {{{ data.label }}}
            </label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
                    <?php foreach (self::get_animations() as $animations_group_name => $animations_group) : ?>
                        <optgroup label="<?php echo esc_attr($animations_group_name); ?>">
                            <?php foreach ($animations_group as $animation_name => $animation_title) : ?>
                                <option value="<?php echo esc_attr($animation_name); ?>">
                                    <?php echo esc_html($animation_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">
            {{{ data.description }}}
        </div>
        <# } #>
        <?php
    }

    public static function get_assets($setting): array
    {
        if (!$setting || 'none' === $setting || !str_contains($setting, 'elementor-animation')) {
            return [];
        }

        return [
            'styles' => ['e-animation-' . str_replace('elementor-animation-', '', $setting)],
        ];
    }
}