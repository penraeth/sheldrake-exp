<?php

return [

//------------------------//
// SYSTEM SETTINGS
//------------------------//

    /**
     * Registration Needs Activation.
     *
     * If set to true users will have to activate their accounts using email account activation.
     */
    'rna' => false,

    /**
     * Login With Email.
     *
     * If set to true users will have to login using email/password combo.
     */
    'lwe' => true, 

    /**
     * Force Strong Password.
     *
     * If set to true users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

//------------------------//
// EMAILS
//------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'admin@example.com', 

    /**
     * Not used in template.
     * You can set support email here.
     */
    'supportEmail' => 'support@example.com',

    /**
     * Not used in template.
     * Used only by internal auto-generated e-mails.
     */
    'fromEmail' => 'experiment@sheldrake.org',


//------------------------//
// MISC
//------------------------//

    'genders' => [
    	'0' => 'Female',
    	'1' => 'Male'
    ],
    
    'relationships' => [
    	'1' => 'Close',
    	'2' => 'Friend',
    	'3' => 'Acquaintance',
    	'4' => 'Stranger'
    ],
    
    'distances' => [
    	'1' => '0',
    	'2' => '<1',
    	'3' => '1+',
    	'4' => '100+',
    	'5' => '1,000+'
    ],
    
    'distance_ranges' => [
    	'1' => 0,
    	'2' => 528,
    	'3' => 5280,
    	'4' => 52800,
    	'5' => 528000
    ],
    
    'serverTimezone' => 'America/Los_Angeles',
    
    
    
];
