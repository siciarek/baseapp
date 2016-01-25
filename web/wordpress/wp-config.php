<?php
/**
 * Podstawowa konfiguracja WordPressa.
 *
 * Skrypt wp-config.php używa tego pliku podczas instalacji.
 * Nie musisz dokonywać konfiguracji przy pomocy przeglądarki internetowej,
 * możesz też skopiować ten plik, nazwać kopię "wp-config.php"
 * i wpisać wartości ręcznie.
 *
 * Ten plik zawiera konfigurację:
 *
 * * ustawień MySQL-a,
 * * tajnych kluczy,
 * * prefiksu nazw tabel w bazie danych,
 * * ABSPATH.
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Ustawienia MySQL-a - możesz uzyskać je od administratora Twojego serwera ** //
/** Nazwa bazy danych, której używać ma WordPress */
define('DB_NAME', 'baseapp');

/** Nazwa użytkownika bazy danych MySQL */
define('DB_USER', 'root');

/** Hasło użytkownika bazy danych MySQL */
define('DB_PASSWORD', 'pass');

/** Nazwa hosta serwera MySQL */
define('DB_HOST', 'localhost');

/** Kodowanie bazy danych używane do stworzenia tabel w bazie danych. */
define('DB_CHARSET', 'utf8mb4');

/** Typ porównań w bazie danych. Nie zmieniaj tego ustawienia, jeśli masz jakieś wątpliwości. */
define('DB_COLLATE', '');

/**#@+
 * Unikatowe klucze uwierzytelniania i sole.
 *
 * Zmień każdy klucz tak, aby był inną, unikatową frazą!
 * Możesz wygenerować klucze przy pomocy {@link https://api.wordpress.org/secret-key/1.1/salt/ serwisu generującego tajne klucze witryny WordPress.org}
 * Klucze te mogą zostać zmienione w dowolnej chwili, aby uczynić nieważnymi wszelkie istniejące ciasteczka. Uczynienie tego zmusi wszystkich użytkowników do ponownego zalogowania się.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ';=C!~[KCE6z+Cj-S$z/<)64E*C5QK-:>Z+?4UCJYC1}|7)1Z3tIJXVy8|e5qc&@n');
define('SECURE_AUTH_KEY',  'JD+QO%Y7WU+dGz6=46#CDX<t^fP0fX?[:C&++X6!1X6.PbOTtJ<YVBj&Cr`|XQaY');
define('LOGGED_IN_KEY',    'i>6&;XQgp+tWYvF8G1[xfES-R=N}@=I.oK|Ry|wJ}VS~K(b0Xbf5+F7,^?+ItZws');
define('NONCE_KEY',        '0cMoIj#|,EQPIue?N:U9=oM9j5HZ!KgI}v%u2A|qf`UtaIMA,]nz/,.oR,TcsYG4');
define('AUTH_SALT',        ',D/C{fE,1%(8:9p;0P@%,NxLt2:` =]$gp^de%KtWQ;H}N7T~H<`@ 0dow0dp}kx');
define('SECURE_AUTH_SALT', 'EG:v%EnA>:#%OK0`Vv*{Y^BB#3%@w=u~O}CQM1Uy:FNXrXq;gKiJQmHytAtn,vLx');
define('LOGGED_IN_SALT',   'z0p09q3/)[oGn$s]T7lyo7R5bS?(bsE>eo%)27SCUiJ}j6O>{77A&u8G:A9#GX9W');
define('NONCE_SALT',       '8(?M/fII.ms5[ r$8Fi@hEX1%pkXl16.ZR(WrrX,XL|z:K`&X(-xGMr@P]X7+lY&');

/**#@-*/

/**
 * Prefiks tabel WordPressa w bazie danych.
 *
 * Możesz posiadać kilka instalacji WordPressa w jednej bazie danych,
 * jeżeli nadasz każdej z nich unikalny prefiks.
 * Tylko cyfry, litery i znaki podkreślenia, proszę!
 */
$table_prefix  = 'wp_';

/**
 * Dla programistów: tryb debugowania WordPressa.
 *
 * Zmień wartość tej stałej na true, aby włączyć wyświetlanie
 * ostrzeżeń podczas modyfikowania kodu WordPressa.
 * Wielce zalecane jest, aby twórcy wtyczek oraz motywów używali
 * WP_DEBUG podczas pracy nad nimi.
 *
 * Aby uzyskać informacje o innych stałych, które mogą zostać użyte
 * do debugowania, przejdź na stronę Kodeksu WordPressa.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* To wszystko, zakończ edycję w tym miejscu! Miłego blogowania! */

/** Absolutna ścieżka do katalogu WordPressa. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Ustawia zmienne WordPressa i dołączane pliki. */
require_once(ABSPATH . 'wp-settings.php');
