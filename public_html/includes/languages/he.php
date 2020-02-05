<?php 

$lang = array(
    'language_name' => 'עברית',
    'css' => array( // css files.
        'index' => 'index.rtl.css?v=0.0.7',
        'panel' => 'dashboard.rtl.css?v=0.1.2' 
        ),
        
    'page_title' => 'בניית אתרים | בניית אתרים בחינם | עיצוב אתרים',
        
    'index' => array(
        // The menu. 
        'menu' => array(
            'דף הבית',
            'אודות',
            'חבילות',
            'תמיכה',
            'help_center' => array( 'terms.html', 'תנאי שימוש' )
            ),
        // Home page.
        'main' => array( 
            'loginButton' => 'כניסה',
            'registerButton' => 'צרו אתר בחינם <i class="fa fa-arrow-left"></i>',
            
            'titleOne' => 'צרו את אתרכם ללא שום עלות!',
            'subTitleOne' => 'עם המערכת הייחודית שפיתחנו בניית אתר עכשיו קלה מתמיד.',
            
            'titleTwo' => 'נהלו את אתרכם בכל מקום, אפילו בנייד 📱',
            'subTitleTwo' => 'האתר שלכם איתכם בכל מקום שתלכו, לעדכן בכל מקום ללא מגבלות!',
            
            'titleThree' => 'עיצובים רנספונסיביים המותאמים לכל סוגי המסכים!',
            'subTitleThree' => 'עומדות באפשרותכם תבניות מרהיבות שמתאימות את עצמן באופן אוטומטי למסכים.',
            
            'titleFour' => 'דברו איתנו אם אתם צריכים משהו :)',
            'subTitleFour' => 'צריכים עזרה? באפשרותכם לקבל עזרה מאנשי התמיכה שלנו.',
            'changeLanguage' => 'החלף לאנגלית',
            'copyright' => 'כל הזכויות שמורות © WebsMaking 2019'
            ), 
        // About us page.
        'about' => array( 
            'title' => 'אודות',
            'text' => 'WebsMaking (או בשם המלא: Website Making) הינו שירות לבניית אתרי אינטרנט בחינם עם מערכת CMS (Content Management System) ידידותית וקלה לשימוש.
                אנו ב- WebsMaking ראינו צורך בלפתח מערכת כזו בשביל לתת מענה למשתמש שאין לו כל ידע קודם בתכנות ובכך לאפשר לכל אדם לפתח אתר עסקי/אישי משלו בלי שום בעיה.
                צוות מתכנתים ומעצבים עמלו ופיתחו את המערכת אשר יצאה לשימוש בשנת 2019.
                עדיין אין לך אתר באינטרנט? לחץ <a href="index.php?do=register">כאן</a> לפתיחת אתר בחינם!
            ', 
            ),
        // Premium packages page.
        'premium' => array( '' ), 
        // Support page.
        'support' => array( '' ),
        // User registration page.
        'register' => array( 
            'errors' => array( 
                'username' => 'שם המשתמש חייב להיות בין 3 ל-12 תווים',
                'password' => 'הסיסמה חייבת להיות בין 6 ל-18 תווים.',
                'email' => 'כתובת המייל לא יכולה להיות קטנה מ-4 תווים.',
                'siteurl' => 'כתובת האתר צריכה להיות בין 3 ל-12 תווים, באותיות לועזיות ובלי סימנים',
                'sitename' => 'שם האתר צריך להיות בין 2 ל-15 תווים.',
                'username_exists' => 'שם המשתמש קיים כבר במערכת.',
                'email_exists' => 'כתובת המייל קיימת כבר במערכת.',
                'siteurl_exists' => 'כתובת האתר שנבחרה לא פנויה.',
                'siteurl_invalid' => 'רק אותיות באנגלית מותרות.',
                'email_invalid' => 'המייל שהוכנס לא כתוב בפורמט חוקי.',
                'success_message' => 'הרשמתך לאתר בוצעה, ואתרך נפתח בהצלחה!'
                ),
            'title' => 'הירשם',
            'subtitle' => 'איזה כיף! בעוד 2 דקות גם לך יהיה אתר באינטרנט :)',
            'username_label' => 'שם משתמש',
            'username_placeHolder' => '3-12 תווים',
            'password_label' => 'סיסמה',
            'password_placeHolder' => '6-18 תווים',
            'mail_label' => 'מייל',
            'siteurl_label' => 'כתובת האתר',
            'siteurl_placeHolder' => 'כתובת האתר (URL)',
            'sitename_label' => 'שם האתר',
            'registerButton' => 'הרשמה',
            ),
        // Login page.
        'login' => array( 
            'errors' => array(
                'cookies' => 'לא יכל ליצור עוגיה.',
                'incorrect' => 'סיסמה ו\או משתמש לא נכונים.',
                ),
            'title' => 'ברוך שובך',
            'username_text' => ' שם משתמש',
            'password_text' => 'סיסמה',
            'forgot_password' => 'שכחת?',
            'loginButton' => 'התחבר'
            ),
        // Reset password page.
        'reset' => array(
            'errors' => array( 
                'password_empty' => 'הסיסמה לא יכולה להישאר ריקה.',
                'confirmPassword_empty' => 'סיסמה חוזרת לא יכולה להישאר ריקה.',
                'passwords_different' => 'הסיסמאות לא יכולות להיות שונות אחת מן השניה.',
                'email_empty' => 'לא הוכנסה כתובת מייל.',
                'email_invalid' => 'הפורמט של כתובת המייל שהוכנסה לא חוקי.',
                'email_doesnt_exist' => 'המייל שהכנסת לא קיים.',
                'success_message' => 'לינק איפוס הסיסמה נשלח במייל.<br>ההודעה נשלחה למייל שצוין, אם לא ראיתם את ההודעה אנא תבדקו גם בתיבת הספאם.'
                ),
            'mail' => array( 
                'subject' => 'איפוס סיסמה.', 
                'message' => array( 
                    'שלום', 
                    'קיבלנו בקשה לאפס את סיסמתך באתרינו.
                    יש ללחוץ על הלינק הבא בשביל לאפס את הסיסמה:', 
                    'במידה ולא ביקשה לשנות את סיסמתך אנא התעלם מהודעה זו.' 
                    )
                ),
            'mail2' => array( 
                'subject' => 'איפוס סיסמה.',
                'message' => 'סיסמתך החדשה הינה',
                'success_message' => 'הסיסמה אופסה ושנלחה אלייך במייל!',
                ),
            'title' => 'איפוס סיסמה',
            'mailPlaceholder' => 'כתובת מייל',
            'resetButton' => 'אפס',
            'newPassword' => 'סיסמה חדשה',
            'confirmNewPassword' => 'סיסמה חדשה שוב',
            'changePassword' => 'שנה סיסמה'
            ),
        
        ),
    'panel' => array(
        'title' => 'לוח בקרה',
        'menu' => array( 
            'close' => 'סגירה',
            'dashboard' => 'לוח בקרה',
            'all_pages' => 'כל הדפים',
            'new_page' => 'דף חדש',
            'edit_top_section' => 'עריכת חלק עליון',
            'edit_side_bar' => 'עריכת חלק צידי',
            'edit_bottom_section' => 'עריכת חלק תחתון',
            'edit_theme' => 'שינוי תבנית',
            'site_settings' => 'הגדרות',
            'watch_site' => 'צפייה באתר',
            'support' => 'תמיכה',
            'logout' => 'התנתקות',
            'security' => 'אבטחה & פרטיות'
            ),
        
        'main' => array( 
            'dashboard' => 'לוח בקרה',
            'site_info' => 'פרטי האתר',
            'system_info' => 'פרטי מערכת',
            
            'site_name' => 'שם האתר',
            'site_address' => 'כתובת האתר',
            'total_pages' => 'כמות דפים',
            'language' => 'שפה',
            'hebrew' => 'עברית',
            'english' => 'אנגלית',
            
            'system_version' => 'גרסת מערכת',
            'disk_space' => 'מקום אחסון',
            'disk_space_unlimited' => '∞',
            'ssl_enabled' => 'מופעל',
            'ssl_disabled' => 'לא פעיל',
            'premium' => 'פרימיום',
            'premium_enabled' => '',
            'premium_disabled' => 'ללא',
            
            
            ),
        'all_pages' => array( 
            'page_management' => 'ניהול דפים',
            'edit_page' => 'עריכה',
            'delete_page' => 'מחיקה',
            'hide_page_from_menu' => 'הסתר מהתפריט',
            'show_page_in_menu' => 'הצג בתפריט',
            'watch_page' => 'עבור לדף',
            'delete_warning' => 'האם אתה בטוח למחוק דף זה לצמיתות?'
            ),
        'edit_page' => array( 
            'title' => 'עריכת הדף',
            'page_name' => 'שם הדף',
            'page_content' => 'תוכן הדף',
            'updatePageButton' => 'שינוי תוכן'
            ),
        'new_page' => array( 
            'title' => 'יצירת דף חדש',
            'page_name' => 'שם הדף',
            'page_content' => 'תוכן הדף',
            'createPageButton' => 'הוסף',
            ),
        'edit_top_section' => array( 
            'title' => 'עריכת חלק עליון',
            'page_content' => 'תוכן הדף',
            'updateButton' => 'עדכן'
            ),
        'edit_bottom_section' => array( 
            'title' => 'עריכת חלק תחתון',
            'page_content' => 'תוכן הדף',
            'updateButton' => 'עדכן'
            ),
        'edit_side_bar' => array( 
            'title' => 'עריכת תפריט צידי',
            'page_content' => 'תוכן התפריט',
            'updateButton' => 'עדכן'
            ),
        'edit_theme' => array( 
            'watchTheme' => 'צפייה בתבנית',
            'theme_already_been_activated' => 'התבנית פעילה כבר',
            'activate_theme' => 'יישם באתר',
            'watch_preview' => 'לצפייה',
            'created_by' => 'ע"י',
            'switch_warning' => 'שים לב! שינוי התבנית הנוכחית יגרום לשינוי התוכן אך ורק בדף הבית, החלק העליון והתחתון. האם אתה בטוח שברצונך לשנות את התבנית?',
            ),
        'site_settings' => array( 
            'errors' => array( 
                'unfamiliar_languages' => 'שפות לא מוכרות.',
                'site_name_empty' => 'שם האתר לא יכול להישאר ריק'
                ),
            'title' => 'הגדרות',
            'site_name' => 'שם האתר',
            'keywords' => 'מילות מפתח',
            'description' => 'תיאור האתר',
            'favicon' => 'אייקון האתר',
            'updateSettingsButton' => 'שינוי הגדרות',
            'site_language' => 'שפת אתר',
            'sidebar_visible' => 'ניראה',
            'sidebar_invisible' => 'בלתי ניראה',
            'sidebar_visibility' => 'ניראות תפריט צידי'
            ),
        'security' => array(
            'errors' => array( 
                'oldPasswordDoesntMatch' => 'הסיסמה הישנה שהכנסת לא מתאימה לזו שנמצאת אצלנו.',
                'PasswordsDontMatch' => 'הסיסמאות החדשות לא תואמות.'
                ),
            'oldPassword' => 'סיסמה ישנה',
            'newPassword' => 'סיסמה חדשה',
            'confirmNewPassword' => 'אישור סיסמה חדשה',
            'changePasswordButton' => 'שנה סיסמה'
            )
        )
);

?>