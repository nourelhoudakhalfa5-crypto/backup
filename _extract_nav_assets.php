<?php
$indexCss = file_get_contents('assets/css/index.css');

$navFooterCss = "/* 
 * nav-footer.css 
 * Centralized styles for the navigation, dropdown, footer, and chatbot. 
 * Extracted from index.css to be shared across all pages.
 */\n\n";

// CSS to extract from index.css
$patternsCss = [
    '/.dropdown-enter\s*\{.*?\}/s',
    '/.dropdown-exit\s*\{.*?\}/s',
    '/.favicon-logo\s*\{.*?\}/s',
    '/.favicon-logo img\s*\{.*?\}/s',
    '/.favicon-logo:hover img\s*\{.*?\}/s',
    '/@keyframes spin\s*\{.*?\}/s',
    '/.menu-item\s*\{.*?\}/s',
    '/.menu-item:hover\s*\{.*?\}/s',
    '/.menu-item::after\s*\{.*?\}/s',
    '/.menu-item:hover::after\s*\{.*?\}/s',
    '/body\.light-mode\s+#navbar-inner\s*\{.*?\}/s',
    '/body\.light-mode\s+#navbar-inner\s+\.bg-black\s*\{.*?\}/s',
    '/body\.light-mode\s+\.line1,\s*body\.light-mode\s+\.line2\s*\{.*?\}/s',
    '/body\.light-mode\s+#line1,\s*body\.light-mode\s+#line2\s*\{.*?\}/s',
    '/\.site-footer\s*\{.*?\}/s',
    '/\.footer-container\s*\{.*?\}/s',
    '/\.footer-logo\s*\{.*?\}/s',
    '/\.social-icons\s*\{.*?\}/s',
    '/\.social-icon\s*\{.*?\}/s',
    '/\.social-icon:hover\s*\{.*?\}/s',
    '/\.footer-newsletter\s*\{.*?\}/s',
    '/\.footer-heading\s*\{.*?\}/s',
    '/\.newsletter-desc\s*\{.*?\}/s',
    '/\.newsletter-form\s*\{.*?\}/s',
    '/\.newsletter-input\s*\{.*?\}/s',
    '/\.newsletter-input::placeholder\s*\{.*?\}/s',
    '/\.newsletter-btn\s*\{.*?\}/s',
    '/\.newsletter-btn:hover\s*\{.*?\}/s',
    '/\.footer-list\s*\{.*?\}/s',
    '/\.footer-list\s+li\s*\{.*?\}/s',
    '/\.footer-list\s+a\s*\{.*?\}/s',
    '/\.footer-list\s+a:hover\s*\{.*?\}/s',
    '/\.footer-bottom\s*\{.*?\}/s',
    '/@media\s*\(max-width:\s*768px\)\s*\{\s*\.footer-container\s*\{.*?\}\s*\.footer-logo\s*\{.*?\}\s*\.social-icons\s*\{.*?\}\s*\.site-footer\s*\{.*?\}\s*\}/s',
    '/\.rdocc-chatbot-btn\s*\{.*?\}/s',
    '/@keyframes rdocc-pulse\s*\{.*?\}/s'
];

foreach ($patternsCss as $pattern) {
    if (preg_match($pattern, $indexCss, $matches)) {
        $navFooterCss .= $matches[0] . "\n\n";
    }
}

// Add animation definitions (fadeSlideDown, fadeSlideUp) from index.css
preg_match('/@keyframes fadeSlideDown\s*\{.*?\}/s', $indexCss, $matches1);
preg_match('/@keyframes fadeSlideUp\s*\{.*?\}/s', $indexCss, $matches2);
if(isset($matches1[0])) $navFooterCss .= $matches1[0] . "\n\n";
if(isset($matches2[0])) $navFooterCss .= $matches2[0] . "\n\n";

file_put_contents('assets/css/nav-footer.css', $navFooterCss);
echo "nav-footer.css created.\n";

// JS to extract from index.js
$indexJs = file_get_contents('assets/js/index.js');
$navJsContent = "(function() {\n";
// Extract menu toggling and closeMenu
if (preg_match('/const menuToggle = document\.getElementById\(\'menu-toggle\'\);.*?document\.addEventListener\(\'click\', \(e\) => \{.*?\}\);/s', $indexJs, $matches)) {
    $navJsContent .= $matches[0] . "\n";
}
$navJsContent .= "})();\n";

file_put_contents('assets/js/nav.js', $navJsContent);
echo "nav.js created.\n";
?>
