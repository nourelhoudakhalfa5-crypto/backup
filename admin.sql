-- Admin Module Database Schema Additions
-- Run this after the main Rdoc_db.sql

USE `rdoc_db`;

-- =============================================
-- Table: subscription_plans
-- =============================================
CREATE TABLE IF NOT EXISTS `subscription_plans` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `prix` DECIMAL(10,2) NOT NULL,
    `duree` ENUM('mois', 'annee') NOT NULL DEFAULT 'mois',
    `nombre_produits` INT DEFAULT 0,
    `nombre_categories` INT DEFAULT 0,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: blog_categories
-- =============================================
CREATE TABLE IF NOT EXISTS `blog_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: blog_posts
-- =============================================
CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `extrait` TEXT,
    `contenu` LONGTEXT,
    `image` VARCHAR(255) DEFAULT NULL,
    `categorie_id` INT DEFAULT NULL,
    `auteur_id` INT DEFAULT NULL,
    `statut` ENUM('publie', 'brouillon') NOT NULL DEFAULT 'brouillon',
    `date_publication` DATETIME DEFAULT NULL,
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`categorie_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (`auteur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: client_subscriptions (link users to plans)
-- =============================================
CREATE TABLE IF NOT EXISTS `client_subscriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `utilisateur_id` INT NOT NULL,
    `plan_id` INT NOT NULL,
    `date_debut` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_fin` DATETIME,
    `statut` ENUM('actif', 'expire', 'annule') NOT NULL DEFAULT 'actif',
    FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;