ci-social-helper
============

Copy in CodeIgniter helpers.


Requirements
------------

1. PHP 5.1+
2. CodeIgniter 1.6.x - 2.1.2

Usage
-----

    $this->load->helper('social_helper');

    $facebook_count = facebook_count('your_facebook_page_id_or_username');
    $twitter_count = twitter_count('your_twitter_username');

    // Optional: Choose if you want to display always same number of digits, it will automatically add zero before the number
    $facebook_count = facebook_count('your_facebook_page_id_or_username', 5);
    -> Will display: 00051 if you have 51 fans.

Simple as that!

