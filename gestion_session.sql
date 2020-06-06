-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 06 juin 2020 à 16:35
-- Version du serveur :  5.7.24
-- Version de PHP : 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_session`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(8, 'bureautique'),
(9, 'web'),
(10, 'test'),
(11, 'bois'),
(12, 'Infographie'),
(13, 'acier'),
(14, 'musique'),
(15, 'couture');

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `categorie_id`, `nom`) VALUES
(15, 8, 'openOffice'),
(17, 9, 'php'),
(18, 8, 'word'),
(19, 11, 'sciage'),
(20, 12, 'DAO'),
(21, 11, 'laminage'),
(22, 11, 'biseautage'),
(23, 13, 'initiation à la forge'),
(25, 9, 'javascript'),
(26, 11, 'masticage'),
(27, 8, 'excel'),
(28, 13, 'charpente'),
(29, 10, 'test'),
(30, 14, 'saxophone'),
(31, 9, 'html'),
(32, 9, 'css'),
(33, 9, 'sql'),
(34, 12, 'PS'),
(36, 12, 'INDD'),
(37, 8, 'PPT'),
(38, 13, 'test'),
(39, 15, 'Point de croix'),
(40, 15, 'tricotage'),
(41, 8, 'steno'),
(42, 9, 'symfony'),
(43, 9, 'REAC');

-- --------------------------------------------------------

--
-- Structure de la table `programme`
--

CREATE TABLE `programme` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `duree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `programme`
--

INSERT INTO `programme` (`id`, `module_id`, `session_id`, `duree`) VALUES
(58, 17, 19, 8),
(59, 25, 19, 10),
(66, 34, 23, 5),
(69, 36, 23, 3),
(75, 39, 24, 10),
(83, 40, 24, 7),
(84, 30, 24, 7),
(93, 42, 19, 100),
(94, 43, 24, 25),
(97, 37, 19, 1),
(98, 30, 19, 7),
(99, 41, 19, 8),
(100, 36, 19, 64),
(116, 17, 24, 7);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `nb_places` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`id`, `nom`, `date_debut`, `date_fin`, `nb_places`) VALUES
(18, 'Secrétariat', '2020-05-25 00:00:00', '2020-08-25 00:00:00', 2),
(19, 'Initiation au web', '2020-05-24 00:00:00', '2021-05-24 00:00:00', 4),
(22, 'Animation', '2020-01-01 00:00:00', '2020-06-01 00:00:00', 5),
(23, 'Initiation infographie', '2020-06-13 00:00:00', '2020-06-20 00:00:00', 10),
(24, 'Faire ses chaussettes soi-même', '2020-01-01 00:00:00', '2021-01-01 00:00:00', 7);

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire`
--

CREATE TABLE `stagiaire` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` tinyint(1) NOT NULL,
  `birth` datetime NOT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stagiaire`
--

INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `sexe`, `birth`, `ville`, `email`, `phone`, `photo`, `create_date`) VALUES
(10, 'pace', 'gregory', 1, '1976-03-19 23:00:00', 'strasbourg', 'gregory.pace@hotmail.fr', '0667371303', 'WIN-20200520-10-50-17-Pro-5ec4f1a8ef958.jpeg', '2020-06-06 16:24:14'),
(31, 'Muller', 'jean-claude', 1, '2000-05-24 00:00:00', 'strasbourg', 'toto@toto.com', '0677889955', 'acheter-un-chat-1-5eca99f9ea603.jpeg', '2020-05-24 15:59:53'),
(32, 'dupont', 'gérard', 1, '1980-09-24 00:00:00', 'Paris', 'jsute@gmail.com', '0607080910', '388923-2019-Porsche-Cayenne-5eca9cb19dcb1.jpeg', '2020-05-24 16:11:29'),
(33, 'koenig', 'claudine', 0, '2001-01-31 00:00:00', 'Mulhouse', 'toto@toto.fr', '0607080910', 'clio-5eca9cf17e18c.jpeg', '2020-05-24 16:12:33'),
(34, 'de gaulle', 'claude', 0, '2000-05-23 22:00:00', 'Paris', 'gerard@gmail.com', '0677889955', NULL, '2020-06-04 13:28:38'),
(35, 'hechbach', 'benoit', 1, '1964-05-26 00:00:00', 'Paris', 'toto@toto.fr', '0677889955', NULL, '2020-05-26 16:45:51'),
(36, 'MURMANN', 'Micka', 1, '1985-01-17 00:00:00', 'STRASBOURG', 'mickael.murmann@elan-formation.fr', '00 00 00 00 00 00', NULL, '2020-05-25 13:35:16'),
(37, 'GIBELLO', 'Virgile', 1, '1984-01-16 00:00:00', 'SCHILTIGHEIM', 'v@gmail.com', '0656565656', 'images-5ece315e820f5.jpeg', '2020-05-27 09:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire_session`
--

CREATE TABLE `stagiaire_session` (
  `stagiaire_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stagiaire_session`
--

INSERT INTO `stagiaire_session` (`stagiaire_id`, `session_id`) VALUES
(10, 18),
(31, 19),
(32, 19),
(33, 18),
(35, 19);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'gregory.pace@hotmail.fr', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$ekYxZUF4RXdDSWNnRjFnQg$9dykrGuXSkgFva86xzBWc+4GqcNIfnFrJFAT62U0ppA'),
(2, 'toto@toto.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$N3p0Z1ZqQTZSLlJJNGw5SA$P076Tiye7YjbnzvzNcRQCHzV+5EEYmDWE/tSFfqdMA0'),
(3, 'tutu@tutu.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$amVTU3NUNVNLbzFDMEloNA$+fFl5Wt5JHGdlC7hDn3u7/vWQ5AA8cGZ2nDmgcZFP2U');

-- --------------------------------------------------------

--
-- Structure de la table `vacances`
--

CREATE TABLE `vacances` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vacances`
--

INSERT INTO `vacances` (`id`, `session_id`, `date_debut`, `date_fin`) VALUES
(16, 24, '2020-02-01 00:00:00', '2020-02-16 00:00:00'),
(37, 19, '2020-06-01 00:00:00', '2020-07-01 00:00:00'),
(38, 19, '2021-01-01 00:00:00', '2021-02-01 00:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C242628BCF5E72D` (`categorie_id`);

--
-- Index pour la table `programme`
--
ALTER TABLE `programme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3DDCB9FFAFC2B591` (`module_id`),
  ADD KEY `IDX_3DDCB9FF613FECDF` (`session_id`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stagiaire`
--
ALTER TABLE `stagiaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stagiaire_session`
--
ALTER TABLE `stagiaire_session`
  ADD PRIMARY KEY (`stagiaire_id`,`session_id`),
  ADD KEY `IDX_D32D02D4BBA93DD6` (`stagiaire_id`),
  ADD KEY `IDX_D32D02D4613FECDF` (`session_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Index pour la table `vacances`
--
ALTER TABLE `vacances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4800690B613FECDF` (`session_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `programme`
--
ALTER TABLE `programme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `stagiaire`
--
ALTER TABLE `stagiaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vacances`
--
ALTER TABLE `vacances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `FK_C242628BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `programme`
--
ALTER TABLE `programme`
  ADD CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_3DDCB9FFAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stagiaire_session`
--
ALTER TABLE `stagiaire_session`
  ADD CONSTRAINT `FK_D32D02D4613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D32D02D4BBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vacances`
--
ALTER TABLE `vacances`
  ADD CONSTRAINT `FK_4800690B613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
