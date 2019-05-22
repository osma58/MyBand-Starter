<?php

function dbConnect() {

	$config = require __DIR__ . '/config.php';

	try {
		$dsn = 'mysql:host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_NAME'];

		$connection = new PDO( $dsn, $config['DB_USER'], $config['DB_PASSWORD'] );

		$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$connection->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

		return $connection;

	} catch ( \PDOException $e ) {
		echo 'Fout bij maken van database verbinding: ' . $e->getMessage();
	}

}

// Interne functie om de juiste URL vanaf localhost te bepalen
// Voor mensen die de website in een subfolder hebben staan
function base_url() {
	global $CONFIG;


	// base directory
	$public_dir = preg_replace( "!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME'] );

	// server protocol
	$protocol = empty( $_SERVER['HTTPS'] ) ? 'http' : 'https';

	// domain name
	$domain = $_SERVER['SERVER_NAME'];

	// base url
	$base_url = preg_replace( "!^${public_dir}!", '', $CONFIG['WEBROOT'] );

	// server port
	$port      = $_SERVER['SERVER_PORT'];
	$disp_port = ( ( $protocol === 'http' && $port === 80 ) || ( $protocol === 'https' && $port === 443 ) ) ? '' : ":$port";

	// put em all together to get the complete base URL
	$url = "${protocol}://${domain}${disp_port}${base_url}";

	return $url;
}

/**
 * Geeft de juiste URL terug voor het opgegeven path
 * Bijvoorbeeld voor de homepage: echo url('/');
 *
 * @param $path
 *
 * @return string
 */
function url( $path ) {
	return base_url() . $path;
}

/**
 * Hier maken we de template engine aan, we geven de template engine het pad naar onze views (templates)
 * @return \League\Plates\Engine
 */
function get_template_engine() {
	global $CONFIG;

	$templates_path  = $CONFIG['PRIVATE'] . '/views';
	$template_engine = new League\Plates\Engine( $templates_path );

	return $template_engine;

}