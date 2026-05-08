-- =============================================
-- RDOC Database Creation Script
-- Base de données: rdoc_db
-- =============================================

CREATE DATABASE IF NOT EXISTS `rdoc_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rdoc_db`;

-- =============================================
-- Table: utilisateurs (clients)
-- =============================================
CREATE TABLE IF NOT EXISTS `utilisateurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `telephone` VARCHAR(20) DEFAULT NULL,
    `adresse` TEXT DEFAULT NULL,
    `role` ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    `date_inscription` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: administrateurs
-- =============================================
CREATE TABLE IF NOT EXISTS `administrateurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: categories
-- =============================================
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: produits
-- =============================================
CREATE TABLE IF NOT EXISTS `produits` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(200) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `prix` DECIMAL(10,2) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `categorie_id` INT DEFAULT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_ajout` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`categorie_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: commandes
-- =============================================
CREATE TABLE IF NOT EXISTS `commandes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `utilisateur_id` INT NOT NULL,
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `statut` ENUM('en_attente', 'confirmee', 'expediee', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente',
    `date_commande` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: details_commande
-- =============================================
CREATE TABLE IF NOT EXISTS `details_commande` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `commande_id` INT NOT NULL,
    `produit_id` INT NOT NULL,
    `quantite` INT NOT NULL DEFAULT 1,
    `prix_unitaire` DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (`commande_id`) REFERENCES `commandes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: avis (reviews)
-- =============================================
CREATE TABLE IF NOT EXISTS `avis` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `utilisateur_id` INT NOT NULL,
    `produit_id` INT NOT NULL,
    `note` TINYINT NOT NULL CHECK (`note` BETWEEN 1 AND 5),
    `commentaire` TEXT DEFAULT NULL,
    `date_avis` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: contacts (contact form submissions)
-- =============================================
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `sujet` VARCHAR(200) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `date_envoi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: newsletter
-- =============================================
CREATE TABLE IF NOT EXISTS `newsletter` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `date_inscription` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insertion de l'administrateur
-- Mot de passe: nour1234 (hashé avec password_hash)
-- Le hash ci-dessous correspond à password_hash('nour1234', PASSWORD_DEFAULT)
-- =============================================
INSERT INTO `administrateurs` (`nom`, `email`, `mot_de_passe`) VALUES
('Admin', 'admin@gmail.com', '$2y$10$pMdHv8jQzRcD/pYFzn2qBO2Dd5tzZAweIt3yfKs1qbrZEXtjWBDkK');
