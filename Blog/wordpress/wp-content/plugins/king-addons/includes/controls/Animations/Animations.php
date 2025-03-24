<?php

namespace King_Addons\Animations;

use Elementor\Base_Data_Control;

if (!defined('ABSPATH')) {
    exit;
}

class Animations extends Base_Data_Control
{
    private static array $_animations = [];

    private static array $pro_animations = [
        'Fade' => [
            'fade-in' => 'Fade In',
            'fade-out' => 'Fade Out',
        ],
        'Slide' => [
            'pro-sltp' => 'Slide Top (Pro)',
            'pro-slrt' => 'Slide Right (Pro)',
            'pro-slxrt' => 'Slide X Right (Pro)',
            'pro-slbt' => 'Slide Bottom (Pro)',
            'pro-sllt' => 'Slide Left (Pro)',
            'pro-slxlt' => 'Slide X Left (Pro)',
        ],
        'Skew' => [
            'pro-sktp' => 'Skew Top (Pro)',
            'pro-skrt' => 'Skew Right (Pro)',
            'pro-skbt' => 'Skew Bottom (Pro)',
            'pro-sklt' => 'Skew Left (Pro)',
        ],
        'Scale' => [
            'pro-scup' => 'Scale Up (Pro)',
            'pro-scdn' => 'Scale Down (Pro)',
        ],
        'Roll' => [
            'pro-rllt' => 'Roll Left (Pro)',
            'pro-rlrt' => 'Roll Right (Pro)',
        ],
    ];

    private static array $free_animations = [
        'Fade' => [
            'fade-in' => 'Fade In',
            'fade-out' => 'Fade Out',
        ],
        'Slide' => [
            'slide-top' => 'Slide Top',
            'slide-right' => 'Slide Right',
            'slide-x-right' => 'Slide X Right',
            'slide-bottom' => 'Slide Bottom',
            'slide-left' => 'Slide Left',
            'slide-x-left' => 'Slide X Left',
        ],
        'Skew' => [
            'skew-top' => 'Skew Top',
            'skew-right' => 'Skew Right',
            'skew-bottom' => 'Skew Bottom',
            'skew-left' => 'Skew Left',
        ],
        'Scale' => [
            'scale-up' => 'Scale Up',
            'scale-down' => 'Scale Down',
        ],
        'Roll' => [
            'roll-left' => 'Roll Left',
            'roll-right' => 'Roll Right',
        ],
    ];

    public function get_type(): string
    {
        return 'king-addons-animations';
    }

    public static function get_animations(): array
    {
        self::$_animations = king_addons_freemius()->can_use_premium_code__premium_only()
            ? self::$free_animations
            : self::$pro_animations;

        return self::$_animations;
    }

    public function content_template(): void
    {
        $control_uid = $this->get_control_uid(); ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">
                {{{ data.label }}}
            </label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
                    <option value="none"><?php echo esc_html__('None', 'king-addons'); ?></option>
                    <?php foreach (self::get_animations() as $group_name => $animations_group) : ?>
                        <optgroup label="<?php echo esc_attr($group_name); ?>">
                            <?php foreach ($animations_group as $animation_key => $animation_val) : ?>
                                <option value="<?php echo esc_attr($animation_key); ?>">
                                    <?php echo esc_html($animation_val); ?>
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
    <?php }
}

class Animations_Alternative extends Animations
{
    public function get_type(): string
    {
        return 'king-addons-animations-alt';
    }

    public function content_template(): void
    {
        $animations = self::get_animations();
        $control_uid = $this->get_control_uid();

        unset($animations['Slide']['slide-x-right'], $animations['Slide']['slide-x-left']);
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">
                {{{ data.label }}}
            </label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
                    <option value="none"><?php echo esc_html__('None', 'king-addons'); ?></option>
                    <?php foreach ($animations as $group_name => $animations_group) : ?>
                        <optgroup label="<?php echo esc_attr($group_name); ?>">
                            <?php foreach ($animations_group as $animation_key => $animation_val) : ?>
                                <option value="<?php echo esc_attr($animation_key); ?>">
                                    <?php echo esc_html($animation_val); ?>
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
    <?php }
}