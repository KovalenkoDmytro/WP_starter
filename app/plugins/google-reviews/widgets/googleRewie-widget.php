<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Essential Elementor Google Review Widget.
 *
 * Elementor widget that fake Google Review help show review instead of using Google resources .
 *
 * @since 1.0.0
 */
class Essential_Elementor_Google_Review_Widget extends \Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);

        wp_enqueue_style('slick', plugin_dir_url( __FILE__ ) . '../assets/style/slick.css');
        wp_enqueue_style('google-review-css', plugin_dir_url( __FILE__ ) . '../assets/style/google-review-widget-style.css');
        wp_enqueue_script('slick', plugin_dir_url( __FILE__ ) . '../assets/js/library/slickSlider.js', [ 'jquery'], null, true);
        wp_enqueue_script('main', plugin_dir_url(__FILE__) . '../assets/js/google-review-widget-script.min.js', [ 'jquery','slick'], null, true );
    }

    /**
     * Get widget name.
     *
     * Google Review widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name(): string
    {
        return 'review';
    }


    /**
     * Get widget title.
     *
     * Google Review widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title(): string
    {
        return esc_html__( 'google review', 'google-review' );
    }

    /**
     * Get widget icon.
     *
     * Google Review widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon(): string
    {
        return 'eicon-review';
    }


    /**
     * Google Review categories.
     *
     * Retrieve the list of categories the Card widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return [ 'general' ];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the Google Review widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords(): array
    {
        return [ 'google', 'review',];
    }


    /**
     * Get widget style.
     *
     * Google Review style.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget name.
     */
    public function get_style_depends(): array
    {
        return [ 'slick', 'google-review-css' ];
    }

    /**
     * Get widget script.
     *
     * Google Review script.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget name.
     */
    public function get_script_depends(): array
    {
        return [ 'main','slick'];
    }


    /**
     * Register Google Review widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls(): void
    {


        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Text section',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__( 'Title', 'textdomain' ),
                'placeholder' => esc_html__( 'Enter your title', 'textdomain' ),
                'default'=>'Good'
            ]
        );

        $this->add_control(
            'stars',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => 'Stars',
                'min' => 3,
                'max' => 5,
                'step' => 0.5,
                'default' => 4,
            ]
        );

        $this->add_control(
            'text',
            [
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'label' => 'Text under stars',
                'placeholder' => 'Based on __ reviews',
                'default'=>'Based on __ reviews'
            ]
        );


        $this->end_controls_section();

        /* start repeater */
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Reviews',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => 'Reviews',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'text',
                        'label' => 'Author Full name',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => 'Jon Smith',
                        'default' => 'Jon Smith',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'avatar_url',
                        'label' => 'link to avatar',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '',
                        'default' => '',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'link',
                        'label' => 'Author Initial',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => 'J',
                        'default' => 'J',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'date',
                        'label' => 'Date of published',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '2024-03-05',
                        'default' => '2024-03-05',
                        'label_block' => true,

                    ],
                    [
                        'name' => 'stars',
                        'label' => 'Stars',
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 3,
                        'max' => 5,
                        'step' => 0.5,
                        'default' => 4,
                    ],
                    [
                        'name' => 'review_description',
                        'label' => 'Review',
                        'type' => \Elementor\Controls_Manager::WYSIWYG,
                        'default' => 'We chose _____ for our extensive kitchen renovation and floor refinishing project. James and team did an incredible job and we are thrilled with the result! During the planning stage, it was evident that James really listened to what we wanted from the project and what our priorities were. We lived in another area of the home while the reno was ongoing and we saw the level of detail and care that they took. The site was clean too. The work schedule and timeline were adhered to, good communication, and transparency with the budget. We highly recommend ____!',
                        'placeholder' => 'Type your review here'
                    ],
                ],
                'title_field' => '{{{ text }}}',
                'default' => [
                    [],[],[],[],
                ]
            ]
        );

        $this->add_control(
            'items_color',
            [
                'label' => esc_html__( 'Color of review items', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__( 'Background color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-widget' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
        /* End repeater */



    }

    /**
     * Render Google Review widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $half_of_star = file_get_contents(plugin_dir_url( __DIR__ ) . 'assets/icons/half_of_star.svg',true);
        $empty_star = file_get_contents(plugin_dir_url( __DIR__ ) . 'assets/icons/empty_star.svg',true);
        $full_star = file_get_contents(plugin_dir_url( __DIR__ ) . 'assets/icons/full_star.svg', true);
        $verified_tick = file_get_contents(plugin_dir_url( __DIR__ ) . 'assets/icons/verified_tick.svg', true);

        ?>

         <div class="review-widget">
             <div class="google-text--container">
                <p class="rating-title"><?=$settings['title']?></p>
                 <div class="stars">
                     <?php
                     $fullNumber = filter_var($settings['stars'], FILTER_VALIDATE_INT);

                     for ($i = 1; $i <= $settings['stars']; $i++){
                         echo $full_star;
                     }
                     if (!$fullNumber) {
                         echo $half_of_star;
                     }
                     for ($i = 1; $i <= round(5 - ceil($settings['stars'])); $i++){
                         echo $empty_star;
                     }
                     ?>

                 </div>
                 <div class="text"><?=$settings['text']?></div>
                 <svg class="google-logo" width="110px" height="35px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 255.2 80.3" style="enable-background:new 0 0 255.2 80.3;" xml:space="preserve"><style type="text/css">.st0{fill:#4285F4;}.st1{fill:#EA4335;}.st2{fill:#FBBC05;}.st3{fill:#34A853;}</style><g id="XMLID_11_"><path id="XMLID_10_" class="st0" d="M31.9,28.6v8.6h20.5c-0.6,4.8-2.2,8.3-4.7,10.8c-3,3-7.7,6.3-15.8,6.3 c-12.6,0-22.5-10.2-22.5-22.8S19.2,8.6,31.9,8.6c6.8,0,11.8,2.7,15.5,6.1l6-6C48.3,3.8,41.4,0,31.9,0C14.6,0,0,14.1,0,31.4 s14.6,31.4,31.9,31.4c9.4,0,16.4-3.1,21.9-8.8c5.7-5.7,7.4-13.6,7.4-20.1c0-2-0.1-3.8-0.5-5.4H31.9z"/><path id="XMLID_24_" class="st1" d="M86.9,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C107.2,30.1,98.1,21.6,86.9,21.6z M86.9,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C98.3,49.1,93,54.2,86.9,54.2z"/><path id="XMLID_21_" class="st0" d="M186.6,26.1h-0.3c-2-2.4-5.8-4.5-10.7-4.5c-10.1,0-19,8.8-19,20.3c0,11.4,8.8,20.3,19,20.3 c4.9,0,8.7-2.2,10.7-4.6h0.3v2.8c0,7.7-4.2,11.9-10.8,11.9c-5.4,0-8.8-3.9-10.2-7.2l-7.7,3.2c2.2,5.4,8.1,12,18,12 c10.4,0,19.3-6.1,19.3-21.1V22.7h-8.4V26.1z M176.4,54.2c-6.1,0-10.8-5.2-10.8-12.3c0-7.2,4.7-12.3,10.8-12.3 c6.1,0,10.8,5.2,10.8,12.4C187.3,49,182.5,54.2,176.4,54.2z"/><path id="XMLID_18_" class="st2" d="M132.3,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C152.6,30.1,143.5,21.6,132.3,21.6z M132.3,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C143.7,49.1,138.4,54.2,132.3,54.2z"/><path id="XMLID_3_" class="st3" d="M202.1,0.8h8.8v61.3h-8.8V0.8z"/><path id="XMLID_14_" class="st1" d="M237.9,54.2c-4.5,0-7.7-2.1-9.8-6.1l27.1-11.2l-0.9-2.3c-1.7-4.5-6.8-12.9-17.3-12.9 c-10.4,0-19.1,8.2-19.1,20.3c0,11.4,8.6,20.3,20.1,20.3c9.3,0,14.7-5.7,16.9-9l-6.9-4.6C245.6,51.9,242.4,54.2,237.9,54.2 L237.9,54.2z M237.3,29.2c3.6,0,6.7,1.9,7.7,4.5l-18.3,7.6C226.6,32.7,232.7,29.2,237.3,29.2z"/></g></svg>
             </div>

             <div class="review-cards">
                 <?php foreach ( $settings['list'] as $item ) : ?>
                     <div class="review-card">
                         <div class="user-container">
                             <div class="user">

                                <?php if (!empty($item['avatar_url'])) : ?>
                                    <img class="avatar" src="<?=$item['avatar_url']?>" alt="<?= $item['text']; ?>" height="40px" width="40px">
                                <?php else:?>
                                    <div class="initial"><?= $item['link']; ?></div>
                                <?php endif?>

                                 <div class="user-info">
                                     <p class="name"><?= $item['text']; ?></p>
                                     <p class="date"><?= $item['date']; ?></p>
                                 </div>

                             </div>
                             <div class="google-icon"><svg width="20px" height="20px" viewBox="-3 0 262 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/></svg></div>
                         </div>

                         <div class="rating">
                             <?php
                             $fullNumber = filter_var($item['stars'], FILTER_VALIDATE_INT);

                             for ($i = 1; $i <= $item['stars']; $i++){
                                 echo $full_star;
                             }
                             if (!$fullNumber) {
                                 echo $half_of_star;
                             }
                             for ($i = 1; $i <= round(5 - ceil($item['stars'])); $i++){
                                 echo $empty_star;
                             }
                             echo $verified_tick;
                             ?>

                         </div>
                         <div class="review-text"><?= $item['review_description']; ?></div>
                     </div>
                 <?php endforeach; ?>
             </div>
         </div>

        <?php
    }

    protected function content_template() {
        ?>

        <div class="review-widget">
            <div class="google-text--container">
                <p class="rating-title">{{{ settings.title }}}</p>
                <div class="stars">
                    <# const fullNumber = Number.isInteger(settings.stars);
                    for (let i = 1; i <= settings.stars; i++) {#>
                    <svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-427.432,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,-14.9846,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(246,187,6);"/></g></g></svg>
                    <# }
                    if (!fullNumber) {#>
                    <svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-447.393,-260.031)"><g transform="matrix(1.01647,0,0,1.01647,4.97715,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(246,187,6);"/></g><g transform="matrix(-1.01647,0,0,1.01647,905.424,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(204,204,204);"/></g></g></svg>
                    <# }
                    for (let i = 1; i <= Math.round(5 - Math.ceil(settings.stars)); i++) {#>
                    <svg width="17px" height="17px" viewBox="0 0 16 15"  xmlns="http://www.w3.org/2000/svg"  xml:space="preserve"  style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-468.008,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,25.5914,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(204,204,204);"/></g></g></svg>
                    <# } #>
                </div>
                <div class="text">{{{ settings.text }}}</div>
                <svg class="google-logo" width="110px" height="35px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 255.2 80.3" style="enable-background:new 0 0 255.2 80.3;" xml:space="preserve"><style type="text/css">.st0{fill:#4285F4;}.st1{fill:#EA4335;}.st2{fill:#FBBC05;}.st3{fill:#34A853;}</style><g id="XMLID_11_"><path id="XMLID_10_" class="st0" d="M31.9,28.6v8.6h20.5c-0.6,4.8-2.2,8.3-4.7,10.8c-3,3-7.7,6.3-15.8,6.3 c-12.6,0-22.5-10.2-22.5-22.8S19.2,8.6,31.9,8.6c6.8,0,11.8,2.7,15.5,6.1l6-6C48.3,3.8,41.4,0,31.9,0C14.6,0,0,14.1,0,31.4 s14.6,31.4,31.9,31.4c9.4,0,16.4-3.1,21.9-8.8c5.7-5.7,7.4-13.6,7.4-20.1c0-2-0.1-3.8-0.5-5.4H31.9z"/><path id="XMLID_24_" class="st1" d="M86.9,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C107.2,30.1,98.1,21.6,86.9,21.6z M86.9,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C98.3,49.1,93,54.2,86.9,54.2z"/><path id="XMLID_21_" class="st0" d="M186.6,26.1h-0.3c-2-2.4-5.8-4.5-10.7-4.5c-10.1,0-19,8.8-19,20.3c0,11.4,8.8,20.3,19,20.3 c4.9,0,8.7-2.2,10.7-4.6h0.3v2.8c0,7.7-4.2,11.9-10.8,11.9c-5.4,0-8.8-3.9-10.2-7.2l-7.7,3.2c2.2,5.4,8.1,12,18,12 c10.4,0,19.3-6.1,19.3-21.1V22.7h-8.4V26.1z M176.4,54.2c-6.1,0-10.8-5.2-10.8-12.3c0-7.2,4.7-12.3,10.8-12.3 c6.1,0,10.8,5.2,10.8,12.4C187.3,49,182.5,54.2,176.4,54.2z"/><path id="XMLID_18_" class="st2" d="M132.3,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C152.6,30.1,143.5,21.6,132.3,21.6z M132.3,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C143.7,49.1,138.4,54.2,132.3,54.2z"/><path id="XMLID_3_" class="st3" d="M202.1,0.8h8.8v61.3h-8.8V0.8z"/><path id="XMLID_14_" class="st1" d="M237.9,54.2c-4.5,0-7.7-2.1-9.8-6.1l27.1-11.2l-0.9-2.3c-1.7-4.5-6.8-12.9-17.3-12.9 c-10.4,0-19.1,8.2-19.1,20.3c0,11.4,8.6,20.3,20.1,20.3c9.3,0,14.7-5.7,16.9-9l-6.9-4.6C245.6,51.9,242.4,54.2,237.9,54.2 L237.9,54.2z M237.3,29.2c3.6,0,6.7,1.9,7.7,4.5l-18.3,7.6C226.6,32.7,232.7,29.2,237.3,29.2z"/></g></svg>
            </div>

        <!-- Slider main container -->
            <div class="review-cards">
                <!-- Slides -->
                <# _.each( settings.list, function( item, index ) { #>
                <div class="review-card">

                    <div class="user-container">
                        <div class="user">
                            <# if (item.avatar_url.length) { #>
                                <img class="avatar" src="{{{ item.avatar_url }}}" alt="{{{ item.text }}}" height="40px" width="40px">
                            <# } else {#>
                                <div class="initial">{{{ item.link }}}</div>
                            <# }#>

                            <div class="user-info">
                                <p class="name">{{{ item.text }}}</p>
                                <p class="date">{{{ item.date }}}</p>
                            </div>

                        </div>
                        <div class="google-icon"><svg width="20px" height="20px" viewBox="-3 0 262 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/></svg></div>
                    </div>

                    <div class="rating">
                        <# const fullNumber = Number.isInteger(item.stars);
                        for (let i = 1; i <= item.stars; i++) {#>
                        <svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-427.432,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,-14.9846,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(246,187,6);"/></g></g></svg>
                        <# }
                        if (!fullNumber) {#>
                        <svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-447.393,-260.031)"><g transform="matrix(1.01647,0,0,1.01647,4.97715,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(246,187,6);"/></g><g transform="matrix(-1.01647,0,0,1.01647,905.424,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(204,204,204);"/></g></g></svg>
                        <# }
                        for (let i = 1; i <= Math.round(5 - Math.ceil(item.stars)); i++) {#>
                        <svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg"  xml:space="preserve"  style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-468.008,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,25.5914,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(204,204,204);"/></g></g></svg>
                        <# } #>
                        <svg id="Layer_2" width="15px" height="15px" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #4285f4;
                                    }

                                    .cls-1, .cls-2 {
                                        fill-rule: evenodd;
                                        stroke-width: 0px;
                                    }

                                    .cls-2 {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                            <g id="Layer_1-2" data-name="Layer 1">
                                <g>
                                    <path class="cls-1" d="M7.29.34c.17-.22.43-.34.71-.34s.54.13.71.34l.78,1.01c.06.08.15.14.25.16.1.03.2.02.3-.02l1.18-.48c.26-.1.54-.08.78.05.24.14.4.38.44.65l.18,1.26c.01.1.06.19.13.26.07.07.16.12.26.13l1.26.18c.27.04.51.2.65.44.14.24.16.53.05.78l-.48,1.18c-.04.09-.04.2-.02.3.03.1.08.18.16.25l1.01.79c.22.17.34.43.34.71s-.13.54-.34.71l-1.01.78c-.08.06-.14.15-.16.25-.03.1-.02.2.02.3l.48,1.18c.1.26.08.54-.05.78-.14.24-.38.4-.65.44l-1.26.18c-.1.01-.19.06-.26.13s-.12.16-.13.26l-.18,1.27c-.04.27-.2.51-.44.65-.24.14-.53.16-.78.05l-1.18-.48c-.09-.04-.2-.04-.3-.02-.1.03-.18.08-.25.16l-.78,1.01c-.17.22-.43.34-.71.34s-.54-.13-.71-.34l-.78-1.01c-.06-.08-.15-.14-.25-.16-.1-.03-.2-.02-.3.02l-1.18.48c-.26.1-.54.08-.78-.05-.24-.14-.4-.38-.44-.65l-.18-1.27c-.01-.1-.06-.19-.13-.26-.07-.07-.16-.12-.26-.13l-1.26-.18c-.27-.04-.51-.2-.65-.44-.14-.24-.16-.53-.05-.78l.48-1.18c.04-.09.04-.2.02-.3-.03-.1-.08-.18-.16-.25l-1.01-.78c-.22-.17-.34-.43-.34-.71s.13-.54.34-.71l1.01-.79c.08-.06.14-.15.16-.25.03-.1.02-.2-.02-.3l-.48-1.18c-.1-.26-.08-.54.05-.78.14-.24.38-.4.65-.44l1.26-.18c.1-.01.19-.06.26-.13.07-.07.12-.16.13-.26l.18-1.26c.04-.27.2-.51.44-.65.24-.14.53-.16.78-.05l1.18.48c.09.04.2.04.3.02.1-.03.18-.08.25-.16l.78-1.01Z"/>
                                    <path class="cls-2" d="M7.74,8.05l2.49-2.5c.19-.19.49-.19.67,0l.67.67c.19.19.19.49,0,.67l-3.32,3.33s-.02.03-.03.04l-.67.67c-.09.09-.22.14-.34.14s-.24-.05-.34-.14l-.67-.67s-.02-.02-.03-.04l-1.74-1.74c-.19-.19-.19-.48,0-.67l.67-.67c.19-.19.49-.19.67,0l1.32,1.32h0s.12.12.12.12h0s1.35,1.33,1.35,1.33l-.82-1.86Z"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="review-text">{{{ item.review_description }}}</div>
                </div>

                <# } ); #>
            </div>

        </div>
        <?php
    }
}


