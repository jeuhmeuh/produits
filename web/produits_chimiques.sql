-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 20 mars 2025 à 21:14
-- Version du serveur :  10.3.29-MariaDB
-- Version de PHP : 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `produits_chimiques`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_batiment_piece`
--

CREATE TABLE `t_batiment_piece` (
  `id` int(11) NOT NULL,
  `batiment` text DEFAULT NULL,
  `lieu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_conditionement`
--

CREATE TABLE `t_conditionement` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_danger_produits`
--

CREATE TABLE `t_danger_produits` (
  `id` int(11) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `danger` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_etat`
--

CREATE TABLE `t_etat` (
  `id` int(11) NOT NULL,
  `etat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_etat_physique`
--

CREATE TABLE `t_etat_physique` (
  `id` int(11) NOT NULL,
  `etat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_inventaire`
--

CREATE TABLE `t_inventaire` (
  `id` int(11) NOT NULL,
  `id_batiment_piece` int(11) DEFAULT NULL,
  `localisation` text DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_etat_physique` int(11) DEFAULT NULL,
  `id_conditionement` int(11) DEFAULT NULL,
  `quantite` text DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `date_peremption` date DEFAULT NULL,
  `date_inventaire` date DEFAULT NULL,
  `id_etat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_phrasesH`
--

CREATE TABLE `t_phrasesH` (
  `id` int(11) NOT NULL,
  `codeH` varchar(200) DEFAULT NULL,
  `phrase` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_produits`
--

CREATE TABLE `t_produits` (
  `id` int(11) NOT NULL,
  `produit` varchar(100) DEFAULT NULL,
  `cas` varchar(50) DEFAULT NULL,
  `cmr` int(11) DEFAULT NULL,
  `explosif` int(11) DEFAULT NULL,
  `inflammable` int(11) DEFAULT NULL,
  `comburant` int(11) DEFAULT NULL,
  `gaz_sous_pression` int(11) DEFAULT NULL,
  `corrosif` int(11) DEFAULT NULL,
  `toxique` int(11) DEFAULT NULL,
  `irritant` int(11) DEFAULT NULL,
  `dange_long_terme` int(11) DEFAULT NULL,
  `dangereux_milieu_aquatique` int(11) DEFAULT NULL,
  `id_fds` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_synonyme_produit`
--

CREATE TABLE `t_synonyme_produit` (
  `id` int(11) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `synonyme` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_batiment_piece`
--
ALTER TABLE `t_batiment_piece`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_conditionement`
--
ALTER TABLE `t_conditionement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_danger_produits`
--
ALTER TABLE `t_danger_produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_produit` (`id_produit`,`danger`);

--
-- Index pour la table `t_etat`
--
ALTER TABLE `t_etat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_etat_physique`
--
ALTER TABLE `t_etat_physique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_inventaire`
--
ALTER TABLE `t_inventaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_produit` (`id_produit`),
  ADD KEY `fk_id_batiment` (`id_batiment_piece`),
  ADD KEY `fk_id_conditionement` (`id_conditionement`),
  ADD KEY `fk_id_etat` (`id_etat`),
  ADD KEY `fk_id_etat_physique` (`id_etat_physique`);

--
-- Index pour la table `t_phrasesH`
--
ALTER TABLE `t_phrasesH`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codeH` (`codeH`);

--
-- Index pour la table `t_produits`
--
ALTER TABLE `t_produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cas` (`cas`);

--
-- Index pour la table `t_synonyme_produit`
--
ALTER TABLE `t_synonyme_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_synonyme` (`id_produit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_batiment_piece`
--
ALTER TABLE `t_batiment_piece`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_conditionement`
--
ALTER TABLE `t_conditionement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_danger_produits`
--
ALTER TABLE `t_danger_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_etat`
--
ALTER TABLE `t_etat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_etat_physique`
--
ALTER TABLE `t_etat_physique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_inventaire`
--
ALTER TABLE `t_inventaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_phrasesH`
--
ALTER TABLE `t_phrasesH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_produits`
--
ALTER TABLE `t_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_synonyme_produit`
--
ALTER TABLE `t_synonyme_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_danger_produits`
--
ALTER TABLE `t_danger_produits`
  ADD CONSTRAINT `fk_id_danger` FOREIGN KEY (`id_produit`) REFERENCES `t_produits` (`id`);

--
-- Contraintes pour la table `t_inventaire`
--
ALTER TABLE `t_inventaire`
  ADD CONSTRAINT `fk_id_batiment` FOREIGN KEY (`id_batiment_piece`) REFERENCES `t_batiment_piece` (`id`),
  ADD CONSTRAINT `fk_id_conditionement` FOREIGN KEY (`id_conditionement`) REFERENCES `t_conditionement` (`id`),
  ADD CONSTRAINT `fk_id_etat` FOREIGN KEY (`id_etat`) REFERENCES `t_etat` (`id`),
  ADD CONSTRAINT `fk_id_etat_physique` FOREIGN KEY (`id_etat_physique`) REFERENCES `t_etat_physique` (`id`),
  ADD CONSTRAINT `fk_id_produit` FOREIGN KEY (`id_produit`) REFERENCES `t_produits` (`id`);

--
-- Contraintes pour la table `t_synonyme_produit`
--
ALTER TABLE `t_synonyme_produit`
  ADD CONSTRAINT `fk_id_synonyme` FOREIGN KEY (`id_produit`) REFERENCES `t_produits` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
