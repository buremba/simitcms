<?php

define('ADMINPATH', Kohana::$config->load('sm-admin.panelurl'));

foreach (Kohana::list_files('init') as $file)
	require_once $file;

Route::set('admin', ADMINPATH.'(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'	 => 'smadmin',
		'controller' => 'dashboard',
		'action'     => 'index',
	));

	
if( Kohana::$config->load('sm-admin.usermodule') ) {
	$GLOBALS['user'] = new Auth();
	
	Route::set('adminauth', function($uri) {
        if (preg_match('/^'.ADMINPATH.'\/(.+)/', $uri, $match) && $uri != ADMINPATH.'/user/login' && $GLOBALS['user']->is_loaded())
        {
            Cookie::set('return', $match[1]);
            return array(
            	'directory'	 => 'smadmin',
                'controller' => 'user',
                'action'     => 'login'
            );
        }
    });
}

Route::set('admini18n', function($uri) {
		if(	Kohana::$config->load('sm-admin.multilanguage') )
		{
			if(Cookie::get(Kohana::$config->load('sm-admin.i18n.cookiename')) != Kohana::$config->load('sm-admin.i18n.defaultlang') && in_array(Cookie::get(Kohana::$config->load('sm-admin.i18n.cookiename')), multi_array_keys(sm_static('Languages')))) {
				I18n::lang(Cookie::get(Kohana::$config->load('sm-admin.i18n.cookiename')));
			}
		}
    }
);