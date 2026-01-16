<?php
/**
 * Shared Meta Tags Partial
 * Include this in all views for consistent SEO and social sharing
 * 
 * Usage: Include at the top of <head> section
 * Variables you can set before including:
 *   $pageTitle - Page specific title
 *   $pageDescription - Page specific description
 *   $pageImage - Page specific image for social sharing
 */

// Default values
$siteName = 'Hostel Management System';
$siteDescription = 'A comprehensive student accommodation management portal. Apply for hostel rooms, manage allocations, track payments, and more.';
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$siteImage = $siteUrl . '/public/assets/img/og-image.png';
$themeColor = '#4F46E5';

// Use page-specific values if set
$metaTitle = isset($pageTitle) ? $pageTitle . ' - ' . $siteName : $siteName;
$metaDescription = $pageDescription ?? $siteDescription;
$metaImage = $pageImage ?? $siteImage;
?>
<!-- Primary Meta Tags -->
<meta name="title" content="<?php echo htmlspecialchars($metaTitle); ?>">
<meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
<meta name="author" content="Hostel Management System">
<meta name="keywords" content="hostel, student accommodation, room booking, hostel management, student housing, dormitory">
<meta name="theme-color" content="<?php echo $themeColor; ?>">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="public/assets/img/favicon.svg">
<link rel="apple-touch-icon" href="public/assets/img/apple-touch-icon.png">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo htmlspecialchars($siteUrl); ?>">
<meta property="og:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($metaImage); ?>">
<meta property="og:site_name" content="<?php echo htmlspecialchars($siteName); ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo htmlspecialchars($siteUrl); ?>">
<meta property="twitter:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
<meta property="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
<meta property="twitter:image" content="<?php echo htmlspecialchars($metaImage); ?>">

<!-- Additional SEO -->
<link rel="canonical" href="<?php echo htmlspecialchars($siteUrl . $_SERVER['REQUEST_URI']); ?>">
<meta name="robots" content="index, follow">
