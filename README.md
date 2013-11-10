Email Relay with PHP
=============================
This code shows how to perform an Email-based double-opt-in registration 
using PHP and MySQL.

It is intended as a complement to my tutorial:
http://tonygaitatzis.tumblr.com/post/66610863603/double-opt-in-email-registration

Configuration
--------------
Set up your settings file to reflect your MySQL server settings.

    $settings['mysql']['server'] = 'localhost';
    $settings['mysql']['username'] = 'mysqluser';
    $settings['mysql']['password'] = 'mysqlpassword';
    $settings['mysql']['schema'] = 'optin_example';

Now You should be good to go.


