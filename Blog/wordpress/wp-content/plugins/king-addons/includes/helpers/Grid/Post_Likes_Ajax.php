<?php

namespace King_Addons;

if (!defined('ABSPATH')) exit;

class Post_Likes_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_nopriv_king_addons_likes_init', [$this, 'likes_init']);
        add_action('wp_ajax_king_addons_likes_init', [$this, 'likes_init']);
    }

    public function likes_init()
    {
        $nonce = isset($_REQUEST['nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'king-addons-post-likes-nonce')) exit(esc_html__('Not permitted', 'king-addons'));

        $js_disabled = !empty($_REQUEST['disabled']);
        $post_id = (!empty($_REQUEST['post_id']) && is_numeric($_REQUEST['post_id'])) ? absint($_REQUEST['post_id']) : '';
        if (!$post_id) return;

        $count = (int)get_post_meta($post_id, '_post_like_count', true);
        if (!$this->already_liked($post_id)) {
            if (is_user_logged_in()) {
                $user_id = get_current_user_id();
                update_user_option($user_id, '_user_like_count', (int)get_user_option('_user_like_count', $user_id) + 1);
                update_post_meta($post_id, '_user_liked', $this->get_user_likes($user_id, $post_id));
            } else {
                update_post_meta($post_id, '_user_IP', $this->get_IP_likes($this->get_IP(), $post_id));
            }
            $like_count = ++$count;
            $response['status'] = 'liked';
        } else {
            if (is_user_logged_in()) {
                $user_id = get_current_user_id();
                $user_like_count = (int)get_user_option('_user_like_count', $user_id);
                if ($user_like_count > 0) {
                    update_user_option($user_id, '_user_like_count', $user_like_count - 1);
                }
                $post_users = $this->get_user_likes($user_id, $post_id);
                if ($post_users) {
                    unset($post_users[array_search($user_id, $post_users, true)]);
                    update_post_meta($post_id, '_user_liked', $post_users);
                }
            } else {
                $post_users = $this->get_IP_likes($this->get_IP(), $post_id);
                if ($post_users) {
                    unset($post_users[array_search($this->get_IP(), $post_users, true)]);
                    update_post_meta($post_id, '_user_IP', $post_users);
                }
            }
            $like_count = ($count > 0) ? --$count : 0;
            $response['status'] = 'unliked';
        }

        update_post_meta($post_id, '_post_like_count', $like_count);
        update_post_meta($post_id, '_post_like_modified', date('Y-m-d H:i:s'));
        $response['count'] = $this->get_like_count($like_count);

        if ($js_disabled) {
            wp_redirect(get_permalink($post_id));
            exit;
        }
        wp_send_json($response);
    }

    public function get_button($post_id, $settings)
    {
        $nonce = wp_create_nonce('king-addons-post-likes-nonce');
        $like_count = (int)get_post_meta($post_id, '_post_like_count', true);
        $button_text = $settings['element_like_text'];
        $default_text_class = $button_text === '' ? ' king-addons-likes-no-default' : '';
        if ($like_count === 0) $default_text_class .= ' king-addons-likes-zero';

        if ($this->already_liked($post_id)) {
            $title = esc_html__('Like', 'king-addons');
            $liked_class = ' king-addons-already-liked';
            $icon_class = str_replace('far', 'fas', $settings['element_like_icon']);
        } else {
            $title = esc_html__('Unlike', 'king-addons');
            $liked_class = '';
            $icon_class = $settings['element_like_icon'];
        }

        $attributes = 'href="' . esc_url(admin_url('admin-ajax.php?action=king_addons_likes_init&post_id=' . $post_id . '&nonce=' . $nonce)) . '"';
        $attributes .= ' class="king-addons-post-like-button' . esc_attr($liked_class . $default_text_class) . '"';
        $attributes .= ' title="' . esc_attr($title) . '"';
        $attributes .= ' data-ajax="' . esc_url(admin_url('admin-ajax.php')) . '"';
        $attributes .= ' data-icon="' . esc_attr($icon_class) . '"';
        $attributes .= ' data-nonce="' . esc_attr($nonce) . '"';
        $attributes .= ' data-post-id="' . esc_attr($post_id) . '"';
        /** @noinspection DuplicatedCode */
        $attributes .= ' data-text="' . esc_attr($button_text) . '"';
        $output = '<a ' . $attributes . '>';
        $output .= '<i class="' . esc_attr($icon_class) . '"></i>';
        $output .= $this->get_like_count($like_count, $button_text);
        $output .= '</a>';
        return $output;
    }

    public function already_liked($post_id)
    {
        $post_meta = is_user_logged_in() ? get_post_meta($post_id, '_user_liked') : get_post_meta($post_id, '_user_IP');
        $post_users = !empty($post_meta[0]) ? $post_meta[0] : [];
        $user_id = is_user_logged_in() ? get_current_user_id() : $this->get_IP();
        return (is_array($post_users) && in_array($user_id, $post_users));
    }

    public function get_user_likes($user_id, $post_id)
    {
        $post_meta = get_post_meta($post_id, '_user_liked');
        $post_users = !empty($post_meta[0]) ? $post_meta[0] : [];
        if (!in_array($user_id, $post_users)) {
            $post_users['user-' . $user_id] = $user_id;
        }
        return $post_users;
    }

    public function get_IP_likes($user_ip, $post_id)
    {
        $post_meta = get_post_meta($post_id, '_user_IP');
        $post_users = !empty($post_meta[0]) ? $post_meta[0] : [];
        if (!in_array($user_ip, $post_users)) {
            $post_users['ip-' . $user_ip] = $user_ip;
        }
        return $post_users;
    }

    public function get_IP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
        } else {
            $ip = !empty($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        return ($ip === false) ? '0.0.0.0' : $ip;
    }

    public function get_format_count($num)
    {
        $precision = 2;
        if ($num >= 1e9) {
            $formatted = number_format($num / 1e9, $precision) . 'B';
        } elseif ($num >= 1e6) {
            $formatted = number_format($num / 1e6, $precision) . 'M';
        } elseif ($num >= 1e3) {
            $formatted = number_format($num / 1e3, $precision) . 'K';
        } else {
            $formatted = $num;
        }
        return str_replace('.00', '', $formatted);
    }

    public function get_like_count($count, $like_text = '')
    {
        $number = (is_numeric($count) && $count > 0) ? $this->get_format_count($count) : $like_text;
        return '<span class="king-addons-post-like-count">' . esc_html($number) . '</span>';
    }
}

new Post_Likes_Ajax();