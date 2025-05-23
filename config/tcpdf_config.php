<?php
// TCPDF Configuration

// Directory containing fonts
define('K_PATH_FONTS', __DIR__ . '/../vendor/tecnickcom/tcpdf/fonts/');

// Default page format
define('PDF_PAGE_FORMAT', 'A4');

// Default page orientation (P=portrait, L=landscape)
define('PDF_PAGE_ORIENTATION', 'L');

// Document creator
define('PDF_CREATOR', 'SGRHBMKH Export System');

// Document author
define('PDF_AUTHOR', 'SGRHBMKH');

// Header logo
define('PDF_HEADER_LOGO', '');
define('PDF_HEADER_LOGO_WIDTH', 0);

// Default unit of measure
define('PDF_UNIT', 'mm');

// Page margins
define('PDF_MARGIN_LEFT', 15);
define('PDF_MARGIN_TOP', 27);
define('PDF_MARGIN_RIGHT', 15);
define('PDF_MARGIN_HEADER', 5);
define('PDF_MARGIN_FOOTER', 10);
define('PDF_MARGIN_BOTTOM', 25);

// Font settings
define('PDF_FONT_NAME_MAIN', 'dejavusans');
define('PDF_FONT_SIZE_MAIN', 10);
define('PDF_FONT_NAME_DATA', 'dejavusans');
define('PDF_FONT_SIZE_DATA', 8);

// Image scale ratio
define('PDF_IMAGE_SCALE_RATIO', 1.25);

// Enable Unicode support
define('PDF_UNICODE', true);

// Set document encryption
define('PDF_PROTECTION', false);
