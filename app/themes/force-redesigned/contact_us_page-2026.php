<?php
/*
 * Template Name: Contact Us Custom
 * Description: A blank template for the Contact Us page.
 */

get_header();

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/contact-us-page2026/style.css?v=3">
  <div class="contact-us-page" xmlns="http://www.w3.org/1999/html">
    <main id="main" class="main container" role="main">

        <div class="contact-us__header">
          <h1 class="contact-us__main-title">Contact us</h1>
        </div>

        <div class="contact-us__content">

          <div class="contact-us__panel contact-us__panel--dark">
            <h2 class="contact-us__sub-title">Contact form</h2>

            <div class="contact-us__form">
              <?php echo do_shortcode('[contact-form-7 id="147" html_id="contact-page-form"]'); ?>
            </div>

          </div>

          <div class="contact-us__panel contact-us__panel--light">

            <ul class="contact-us__info-list">

              <li class="contact-us__info-item">
                <svg class="contact-us__icon contact-us__icon--phone" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                  <path d="M20.7626 22C17.9029 22 15.2125 21.4592 12.6915 20.3775C10.1706 19.2958 7.97075 17.8152 6.09212 15.9357C4.21349 14.0569 2.72862 11.8569 1.63752 9.3357C0.546416 6.8145 0.00123084 4.11547 0.00196411 1.2386C-0.0163676 0.9086 0.0936225 0.619667 0.331935 0.3718C0.570247 0.123933 0.863554 0 1.21186 0H5.50147C5.83144 0 6.11558 0.123933 6.3539 0.3718C6.59221 0.619667 6.71137 0.9174 6.71137 1.265C6.71137 2.07167 6.76636 2.8325 6.87635 3.5475C6.98634 4.2625 7.16049 4.94083 7.3988 5.5825C7.49046 5.78417 7.50879 5.99023 7.4538 6.2007C7.3988 6.41117 7.28881 6.60843 7.12383 6.7925L4.37407 9.5425C5.16234 11.1558 6.28057 12.6867 7.72877 14.135C9.17698 15.5833 10.7352 16.7383 12.4034 17.6L15.1531 14.85C15.3181 14.685 15.5106 14.575 15.7306 14.52C15.9505 14.465 16.1705 14.4833 16.3905 14.575C17.0688 14.795 17.7701 14.9648 18.4946 15.0843C19.2191 15.2038 19.9659 15.2632 20.7351 15.2625C21.0834 15.2625 21.3815 15.3908 21.6293 15.6475C21.8772 15.9042 22.0007 16.2158 22 16.5825V20.79C22 21.12 21.8808 21.4042 21.6425 21.6425C21.4042 21.8808 21.1109 22 20.7626 22ZM14.4382 18.7C15.2631 19.0117 16.1247 19.2592 17.0229 19.4425C17.9212 19.6258 18.8469 19.745 19.8002 19.8V17.435C19.2319 17.3983 18.6497 17.3342 18.0536 17.2425C17.4574 17.1508 16.8664 17.0133 16.2805 16.83L14.4382 18.7ZM19.8002 11C19.8002 8.54333 18.9478 6.4625 17.2429 4.7575C15.5381 3.0525 13.4574 2.2 11.001 2.2V0C12.5225 0 13.9524 0.288933 15.2906 0.8668C16.6288 1.44467 17.7929 2.22823 18.7828 3.2175C19.7727 4.2075 20.5566 5.37167 21.1344 6.71C21.7122 8.04833 22.0007 9.47833 22 11H19.8002ZM15.4006 11C15.4006 9.79 14.9698 8.75417 14.1082 7.8925C13.2466 7.03083 12.2109 6.6 11.001 6.6V4.4C12.8342 4.4 14.3923 5.04167 15.6756 6.325C16.9588 7.60833 17.6004 9.16667 17.6004 11H15.4006ZM3.30167 7.5075L5.11651 5.665C4.96985 5.13333 4.84593 4.57417 4.74474 3.9875C4.64355 3.40083 4.57499 2.805 4.53906 2.2H2.22927C2.26593 3.08 2.37592 3.96 2.55924 4.84C2.74255 5.72 2.99003 6.60917 3.30167 7.5075Z" fill="black"/>
                </svg>
                <a href="tel:4032566501" class="contact-us__link">403.<span>256</span>.6501</a>
              </li>

              <li class="contact-us__info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                  <path d="M22 2.2C22 0.99 21.01 0 19.8 0H2.2C0.99 0 0 0.99 0 2.2V15.4C0 16.61 0.99 17.6 2.2 17.6H19.8C21.01 17.6 22 16.61 22 15.4V2.2ZM19.8 2.2L11 7.7L2.2 2.2H19.8ZM19.8 15.4H2.2V4.4L11 9.9L19.8 4.4V15.4Z" fill="black"/>
                </svg>
                <a href="mailto:info@forceautostyling.com" class="contact-us__link">info@forceautostyling.com</a>
              </li>

              <li class="contact-us__info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                  <path d="M11.4983 21C16.7451 21 20.9983 16.7468 20.9983 11.5C20.9983 6.2533 16.7451 2 11.4983 2C6.25159 2 1.99829 6.2533 1.99829 11.5C1.99829 16.7468 6.25159 21 11.4983 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M10.9983 8V11.663L14.9983 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="contact-us__text-group">
                  Monday – Friday 8am to 5pm <br> Closed weekends & holidays and email
                </span>
              </li>
            </ul>

            <div class="contact-us__location">
              <div class="contact-us__address-row">
                <svg class="contact-us__icon contact-us__icon--pin" xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 20 24" fill="none">
                  <path d="M10 12C10.6875 12 11.2762 11.7648 11.7663 11.2944C12.2563 10.824 12.5008 10.2592 12.5 9.6C12.5 8.94 12.255 8.3748 11.765 7.9044C11.275 7.434 10.6867 7.1992 10 7.2C9.3125 7.2 8.72375 7.4352 8.23375 7.9056C7.74375 8.376 7.49917 8.9408 7.5 9.6C7.5 10.26 7.745 10.8252 8.235 11.2956C8.725 11.766 9.31333 12.0008 10 12ZM10 24C6.64583 21.26 4.14083 18.7152 2.485 16.3656C0.829167 14.016 0.000833333 11.8408 0 9.84C0 6.84 1.00542 4.45 3.01625 2.67C5.02708 0.89 7.355 0 10 0C12.6458 0 14.9742 0.89 16.985 2.67C18.9958 4.45 20.0008 6.84 20 9.84C20 11.84 19.1717 14.0152 17.515 16.3656C15.8583 18.716 13.3533 21.2608 10 24Z" fill="black"/>
                </svg>

                <address class="contact-us__address">
                  <a href="https://maps.app.goo.gl/P7LE18HEMvsQGCxK7" target="_blank">
                    5539 6th Street SE<br>
                    Calgary, AB T2H 1L6
                  </a>
                </address>
              </div>
              <div class="contact-us__map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2938.9063077226015!2d-114.04962929999999!3d51.0040225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5371705b3a172107%3A0xa6d1cf571c7b2ef4!2sForce%20Auto%20Styling%20%26%20Mechanic!5e1!3m2!1sen!2sca!4v1772151000566!5m2!1sen!2sca" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>



          </div>

        </div>

    </main></div><?php

get_footer();