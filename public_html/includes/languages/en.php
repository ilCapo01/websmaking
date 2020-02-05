<?php 

$lang = array(
    'language_name' => 'English',
    'css' => array( // css files.
        'index' => 'index.rtl.css',
        'panel' => 'dashboard.ltr.css?v=0.0.3' 
        ),
        
    'page_title' => 'Free Website Building',
    
    'index' => array(
        // The menu. 
        'menu' => array(
            'Home',
            'About us',
            'Premium',
            'Support',
            'help_center' => array( 'terms.html', 'Terms' )
            ),
        // Home page.
        'main' => array( 
            'loginButton' => 'Log in',
            'registerButton' => 'Get a free website <i class="fa fa-arrow-right"></i>',
            
            'titleOne' => 'Create Your Own Website For Free!',
            'subTitleOne' => 'With the unique system we have developed, building a website<br><br><br>is now easier than ever.',
            
            'titleTwo' => 'Manage your website everywhere, even on mobile 📱',
            'subTitleTwo' => 'Your site with you wherever you go, manage anywhere with no Limits!',
            
            'titleThree' => 'Our responsive templates fit any resolution!',
            'subTitleThree' => 'Spectacular responsive templates are available for you instantly.',
            
            'titleFour' => 'Stay in touch with us :)',
            'subTitleFour' => 'Need help? our support staff is at your disposal.',
            
            'changeLanguage' => 'Change to Hebrew',
            'copyright' => 'All rights reserved © WebsMaking 2019'
            ), 
        // About us page.
        'about' => array(
            'title' => 'About us',
            'text' => '
            WebsMaking (a.k.a "Website Making") is a free service for website building with<br> a CMS (Content Management System).<br>
            We, at WebsMaking have identified a need for a simple lightweight CMS, for people who dont have the knowledge as of how to make a website
            from scratch, and as a result we\'ve made it, to allow any type of person to build his\\her own website<br>without the need to pay excess of money.<br>
            Our CMS is available as of 2019. Still dont have a website ? create your own now in 2 minutes for free by clicking <a href="'.ABS_PATH.'?do=register">here</a>.
            ', 
            ),
        // Premium packages page.
        'premium' => array( '' ), 
        // Support page.
        'support' => array( '' ),
        // User registration page.
        'register' => array( 
            'errors' => array( 
                'username' => 'The username must be between 3-12 characters.',
                'password' => 'The password must be between 6-18 characters.',
                'email' => 'The email address cannot be less than 4 characters.',
                'siteurl' => 'The URL should be between 3-12 characters, no with numbers.',
                'sitename' => 'The site name should be between 2-15 characters.',
                'username_exists' => 'The username already exists.',
                'email_exists' => 'The email address already exists.',
                'siteurl_exists' => 'The selected URL is not available',
                'siteurl_invalid' => 'Only English letters are allowed.',
                'email_invalid' => 'The inserted email is not written in legal format.',
                'success_message' => 'The registration was successful! Your site is created and you can to manage it now.',
                ),
            'title' => 'Sign up',
            'subtitle' => 'In 2 more minutes your website will be up and running :)',
            'username_label' => 'Username',
            'username_placeHolder' => '3-12 characters',
            'password_label' => 'Password',
            'password_placeHolder' => '6-18 characters',
            'mail_label' => 'Email',
            'siteurl_label' => 'Website URL',
            'siteurl_placeHolder' => 'Your URL',
            'sitename_label' => 'Website name',
            'registerButton' => 'Create a Website!',
            ),
        // Login page.
        'login' => array( 
            'errors' => array(
                'cookies' => 'Cannot create a cookie.',
                'incorrect' => ' Wrong password and / or username. ',
                ),
            'title' => ' Admin login ',
            'username_text' => 'Username',
            'password_text' => 'Password',
            'forgot_password' => 'Forgot your password?',
            'loginButton' => 'Login'
            ),
        // Reset password page.
        'reset' => array(
            'errors' => array( 
                'password_empty' => 'The password cannot stay empty',
                'confirmPassword_empty' => 'The password again cannot stay empty',
                'passwords_different' => 'The passwords cannot be different from one another.',
                'email_empty' => 'No email entered.',
                'email_invalid' => 'The inserted email is not written in legal format.',
                'email_doesnt_exist' => 'The email you entered does not exist.',
                'success_message' => 'The password reset link was emailed. <br> The message was sent to the specified email, if you have not seen the message please check the spam box as well.'
                ),
            'mail' => array( 
                'subject' => 'password reset.', 
                'message' => array( 
                    'Hello', 
                    'We received a request to reset your password on our site.
                    Click the following link to reset the password:',
                    'If you did not want to change your password, please ignore this message.'
                    )
                ),
            'mail2' => array( 
                'subject' => 'password reset.',
                'message' => 'x Your new password is',
                'success_message' => 'Password reset sent to me by email!',
                ),
            'title' => 'Password reset',
            'mailPlaceholder' => 'Email',
            'resetButton' => 'Reset',
            'newPassword' => 'New password',
            'confirmNewPassword' => 'New password again',
            'changePassword' => 'Change password'
            ),
        
        ),
    'panel' => array(
        'title' => 'Dashboard',
        'menu' => array( 
            'close' => 'Close',
            'dashboard' => 'Dashboard',
            'all_pages' => 'All pages',
            'new_page' => 'New page',
            'edit_top_section' => 'Edit header',
            'edit_side_bar' => 'Edit sidebar',
            'edit_bottom_section' => 'Edit footer',
            'edit_theme' => 'Styles',
            'site_settings' => 'Settings',
            'watch_site' => 'Website View',
            'support' => 'Support',
            'logout' => 'Log out',
            'security' => 'Security & Privacy'
            ),
        
        'main' => array( 
            'dashboard' => 'Dashboard',
            'site_info' => 'Website Details',
            'system_info' => 'System Details',
            
            'site_name' => 'Website name',
            'site_address' => 'Website URL',
            'total_pages' => 'Pages',
            'language' => 'Language',
            'hebrew' => 'Hebrew',
            'english' => 'English',
            
            'system_version' => 'System version',
            'disk_space' => 'Disk Usage',
            'disk_space_unlimited' => '∞',
            'ssl_enabled' => 'Enabled',
            'ssl_disabled' => 'Disabled',
            'premium' => 'Premium',
            'premium_enabled' => 'Enabled',
            'premium_disabled' => 'Non',
            
            
            ),
        'all_pages' => array( 
            'page_management' => 'Pages management',
            'edit_page' => 'Edit',
            'delete_page' => 'Delete',
            'hide_page_from_menu' => 'Hide',
            'show_page_in_menu' => 'Show',
            'watch_page' => 'Go to page',
            'delete_warning' => 'You sure you want to delete this page?'
            ),
        'edit_page' => array( 
            'title' => 'Edit the page',
            'page_name' => 'Page name',
            'page_content' => 'Page content',
            'updatePageButton' => 'Change'
            ),
        'new_page' => array( 
            'title' => 'New page',
            'page_name' => 'Name page',
            'page_content' => 'Page content',
            'createPageButton' => 'Add',
            ),
        'edit_top_section' => array( 
            'title' => 'Edit header',
            'page_content' => 'content',
            'updateButton' => 'Update'
            ),
        'edit_bottom_section' => array( 
            'title' => 'Edit footer',
            'page_content' => 'content',
            'updateButton' => 'Update'
            ),
        'edit_side_bar' => array( 
            'title' => 'Edit side bar',
            'page_content' => 'content',
            'updateButton' => 'Update'
            ),
        'edit_theme' => array( 
            'watchTheme' => 'View',
            'theme_already_been_activated' => 'Already active',
            'activate_theme' => 'Select',
            'watch_preview' => 'Check out',
            'created_by' => 'By',
            'switch_warning' => 'warning! Changing the current template will only change the content on the home, header and bottom part. Are you sure you want to change the template?'
            ),
        'site_settings' => array( 
            'errors' => array( 
                'unfamiliar_languages' => 'Unfamiliar language',
                'site_name_empty' => 'Site\'s name cannot remain empty.'
                ),
            'title' => 'Settings',
            'site_name' => 'Site name',
            'keywords' => 'Keywords',
            'description' => 'Site description',
            'favicon' => 'Site favicon',
            'updateSettingsButton' => 'Update settings',
            'site_language' => 'Site language',
            'sidebar_visible' => 'Visible',
            'sidebar_invisible' => 'Invisible',
            'sidebar_visibility' => 'Sidebar visibility'
            ),
        'security' => array(
            'errors' => array( 
                'oldPasswordDoesntMatch' => 'The old the password you typed doesn\'t match the one we have in our database.',
                'PasswordsDontMatch' => 'The new password and its confirmation doesn\'t match.'
                ),
            'oldPassword' => 'Old password',
            'newPassword' => 'New password',
            'confirmNewPassword' => 'Confirm new password',
            'changePasswordButton' => 'Change'
            )
        )
);

?>