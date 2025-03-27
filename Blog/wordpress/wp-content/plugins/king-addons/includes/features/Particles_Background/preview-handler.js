// noinspection SpellCheckingInspection

"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        let KngParticlesBackgroundHandler = elementorModules.frontend.handlers.Base.extend({
            onInit: function onInit() {
                if (this.$element.hasClass('kng-particles-bg-yes')) {
                    this.applyKngParticlesBackground();
                }
            },
            getReadySettings: function getReadySettings() {
                let type = this.getElementSettings('kng_particles_bg_type');
                let color = this.getElementSettings('kng_particles_bg_color');
                let z_index = this.getElementSettings('kng_particles_bg_z_index');
                let config, speed, shape_type, move_direction, number, size;
                switch (type) {
                    case 'default':
                        speed = this.getElementSettings('kng_particles_bg_anim_speed_default');
                        shape_type = this.getElementSettings('kng_particles_bg_shape_type');
                        move_direction = this.getElementSettings('kng_particles_bg_move_direction');
                        number = this.getElementSettings('kng_particles_bg_number_default');
                        size = this.getElementSettings('kng_particles_bg_size_default');
                        config = this.getParticlesJSON(color, speed, shape_type, move_direction, number, size).default;
                        break;
                    case 'nasa':
                        speed = this.getElementSettings('kng_particles_bg_anim_speed_nasa');
                        shape_type = this.getElementSettings('kng_particles_bg_shape_type');
                        move_direction = this.getElementSettings('kng_particles_bg_move_direction');
                        number = this.getElementSettings('kng_particles_bg_number_nasa');
                        size = this.getElementSettings('kng_particles_bg_size_nasa');
                        config = this.getParticlesJSON(color, speed, shape_type, move_direction, number, size).nasa;
                        break;
                    case 'bubble':
                        speed = this.getElementSettings('kng_particles_bg_anim_speed_bubble');
                        shape_type = this.getElementSettings('kng_particles_bg_shape_type_bubble');
                        move_direction = this.getElementSettings('kng_particles_bg_move_direction');
                        number = this.getElementSettings('kng_particles_bg_number_bubble');
                        size = this.getElementSettings('kng_particles_bg_size_bubble');
                        config = this.getParticlesJSON(color, speed, shape_type, move_direction, number, size).bubble;
                        break;
                    case 'snow':
                        speed = this.getElementSettings('kng_particles_bg_anim_speed_snow');
                        shape_type = this.getElementSettings('kng_particles_bg_shape_type');
                        move_direction = this.getElementSettings('kng_particles_bg_move_direction_snow');
                        number = this.getElementSettings('kng_particles_bg_number_snow');
                        size = this.getElementSettings('kng_particles_bg_size_snow');
                        config = this.getParticlesJSON(color, speed, shape_type, move_direction, number, size).snow;
                        break;
                    case 'nyan_cat':
                        speed = this.getElementSettings('kng_particles_bg_anim_speed_nyan_cat');
                        shape_type = this.getElementSettings('kng_particles_bg_shape_type_nyan_cat');
                        move_direction = this.getElementSettings('kng_particles_bg_move_direction_nyan_cat');
                        number = this.getElementSettings('kng_particles_bg_number_nyan_cat');
                        size = this.getElementSettings('kng_particles_bg_size_nyan_cat');
                        config = this.getParticlesJSON(color, speed, shape_type, move_direction, number, size).nyan_cat;
                        break;
                    case 'custom_code':
                        config = this.getElementSettings('kng_particles_bg_custom_code');
                        break;
                }
                return [type, config, z_index];
            },
            onElementChange: function onElementChange() {
                if (this.$element.hasClass('kng-particles-bg-yes')) {
                    if (!this.$element.hasClass('kng-particles-bg-applied')) {
                        this.applyKngParticlesBackground();
                    }
                } else if (this.$element.hasClass('kng-particles-bg-applied')) {
                    if (!this.$element.hasClass('kng-particles-bg-yes')) {
                        this.$element.removeClass('kng-particles-bg-applied');
                        let element_ID = this.$element.data('id');
                        $('.king-addons-particles-js-' + element_ID).remove();
                        $('#king-addons-particles-container-' + element_ID).remove();
                    }
                }
            },
            applyKngParticlesBackground: function applyKngParticlesBackground() {
                let [type, config, z_index] = this.getReadySettings();
                let element_ID = this.$element.data('id');
                let config_code;

                if (type !== 'custom_code') {
                    config_code = JSON.stringify(config);
                } else {
                    config_code = config;
                }

                this.$element.addClass('kng-particles-bg-applied');
                $('.king-addons-particles-js-' + element_ID).remove();
                $('#king-addons-particles-container-' + element_ID + ' .particles-js-canvas-el').remove();
                this.$element.prepend('<div id="king-addons-particles-container-' + element_ID + '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index:' + z_index + ';"></div>');
                this.$element.after('<script class="king-addons-particles-js-' + element_ID + '">particlesJS("king-addons-particles-container-' + element_ID + '", ' + config_code + ');</script>');
            },
            getParticlesJSON: function getParticlesJSON(color, speed, shape_type, move_direction, number, size) {
                return {
                    default: {
                        "particles": {
                            "number": {
                                "value": number,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": color
                            },
                            "shape": {
                                "type": shape_type,
                                "stroke": {
                                    "width": 0,
                                    "color": "#000000"
                                },
                                "polygon": {
                                    "nb_sides": 5
                                }
                            },
                            "opacity": {
                                "value": 0.5,
                                "random": false,
                                "anim": {
                                    "enable": false,
                                    "speed": 1,
                                    "opacity_min": 0.1,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": size,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 40,
                                    "size_min": 0.1,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": true,
                                "distance": 150,
                                "color": color,
                                "opacity": 0.4,
                                "width": 1
                            },
                            "move": {
                                "enable": true,
                                "speed": speed,
                                "direction": move_direction,
                                "random": false,
                                "straight": false,
                                "out_mode": "out",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 600,
                                    "rotateY": 1200
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "repulse"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "push"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 400,
                                    "line_linked": {
                                        "opacity": 1
                                    }
                                },
                                "bubble": {
                                    "distance": 400,
                                    "size": 40,
                                    "duration": 2,
                                    "opacity": 8,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 200,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    },
                    nasa: {
                        "particles": {
                            "number": {
                                "value": number,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": color
                            },
                            "shape": {
                                "type": shape_type,
                                "stroke": {
                                    "width": 0,
                                    "color": "#000000"
                                },
                                "polygon": {
                                    "nb_sides": 5
                                }
                            },
                            "opacity": {
                                "value": 1,
                                "random": true,
                                "anim": {
                                    "enable": true,
                                    "speed": 1,
                                    "opacity_min": 0,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": size,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 4,
                                    "size_min": 0.3,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 150,
                                "color": "#ffffff",
                                "opacity": 0.4,
                                "width": 1
                            },
                            "move": {
                                "enable": true,
                                "speed": speed,
                                "direction": move_direction,
                                "random": true,
                                "straight": false,
                                "out_mode": "out",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 600,
                                    "rotateY": 600
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "bubble"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "repulse"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 400,
                                    "line_linked": {
                                        "opacity": 1
                                    }
                                },
                                "bubble": {
                                    "distance": 250,
                                    "size": 0,
                                    "duration": 2,
                                    "opacity": 0,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 400,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    },
                    bubble: {
                        "particles": {
                            "number": {
                                "value": number,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": color
                            },
                            "shape": {
                                "type": shape_type,
                                "stroke": {
                                    "width": 0,
                                    "color": "#000"
                                },
                                "polygon": {
                                    "nb_sides": 6
                                }
                            },
                            "opacity": {
                                "value": 0.3,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 1,
                                    "opacity_min": 0.1,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": size,
                                "random": false,
                                "anim": {
                                    "enable": true,
                                    "speed": 10,
                                    "size_min": 40,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 200,
                                "color": "#ffffff",
                                "opacity": 1,
                                "width": 2
                            },
                            "move": {
                                "enable": true,
                                "speed": speed,
                                "direction": move_direction,
                                "random": false,
                                "straight": false,
                                "out_mode": "out",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 600,
                                    "rotateY": 1200
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "grab"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "push"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 400,
                                    "line_linked": {
                                        "opacity": 1
                                    }
                                },
                                "bubble": {
                                    "distance": 400,
                                    "size": 40,
                                    "duration": 2,
                                    "opacity": 8,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 200,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    },
                    snow: {
                        "particles": {
                            "number": {
                                "value": number,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": color
                            },
                            "shape": {
                                "type": shape_type,
                                "stroke": {
                                    "width": 0,
                                    "color": "#000000"
                                },
                                "polygon": {
                                    "nb_sides": 5
                                }
                            },
                            "opacity": {
                                "value": 0.5,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 1,
                                    "opacity_min": 0.1,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": size,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 40,
                                    "size_min": 0.1,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 500,
                                "color": "#ffffff",
                                "opacity": 0.4,
                                "width": 2
                            },
                            "move": {
                                "enable": true,
                                "speed": speed,
                                "direction": move_direction,
                                "random": false,
                                "straight": false,
                                "out_mode": "out",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 600,
                                    "rotateY": 1200
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "bubble"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "repulse"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 400,
                                    "line_linked": {
                                        "opacity": 0.5
                                    }
                                },
                                "bubble": {
                                    "distance": 400,
                                    "size": 4,
                                    "duration": 0.3,
                                    "opacity": 1,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 200,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    },
                    nyan_cat: {
                        "particles": {
                            "number": {
                                "value": number,
                                "density": {
                                    "enable": false,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": color
                            },
                            "shape": {
                                "type": shape_type,
                                "stroke": {
                                    "width": 0,
                                    "color": "#000000"
                                },
                                "polygon": {
                                    "nb_sides": 5
                                }
                            },
                            "opacity": {
                                "value": 0.5,
                                "random": false,
                                "anim": {
                                    "enable": false,
                                    "speed": 1,
                                    "opacity_min": 0.1,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": size,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 40,
                                    "size_min": 0.1,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 150,
                                "color": "#ffffff",
                                "opacity": 0.4,
                                "width": 1
                            },
                            "move": {
                                "enable": true,
                                "speed": speed,
                                "direction": move_direction,
                                "random": false,
                                "straight": true,
                                "out_mode": "out",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 600,
                                    "rotateY": 1200
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "grab"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "repulse"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 200,
                                    "line_linked": {
                                        "opacity": 1
                                    }
                                },
                                "bubble": {
                                    "distance": 400,
                                    "size": 40,
                                    "duration": 2,
                                    "opacity": 8,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 200,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    }
                };
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(KngParticlesBackgroundHandler, {
                $element: $scope
            });
        });

    });
})(jQuery);