-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 30 Avril 2021 à 08:29
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_pretpi_appartements`
--
CREATE database db_pretpi_appartements;
USE db_pretpi_appartements;

-- --------------------------------------------------------

--
-- Structure de la table `t_appartement`
--

CREATE TABLE `t_appartement` (
  `idAppartement` bigint(20) UNSIGNED NOT NULL,
  `appName` varchar(50) NOT NULL,
  `appDescription` varchar(255) DEFAULT NULL,
  `appCategory` varchar(50) DEFAULT NULL,
  `appImage` varchar(255) DEFAULT 'defaultAppartementPicture.jpg',
  `appSurface` float DEFAULT NULL,
  `appPrix` float NOT NULL,
  `appDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `appRate` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_appartement`
--

INSERT INTO `t_appartement` (`idAppartement`, `appName`, `appDescription`, `appCategory`, `appImage`, `appSurface`, `appPrix`, `appDate`, `appRate`, `idUser`) VALUES
(1, 'App1', 'appartement 1', 'Appartement', 'appartement001.jpg', 15, 800, '2021-04-27 00:00:00', 1, 2),
(2, 'Maison1', 'une jolie maison', 'Maison', 'house001.jpg', 4850, 10000, '2021-04-27 00:00:00', 2, 2),
(3, 'App0', 'appartement par défaut', 'Appartement', 'defaultAppartementPicture.jpg', 10, 900, '2021-04-27 00:00:00', 1, 2),
(4, 'hrhhhhhhhhhhhh', 'dfjhvbbfd', 'fghfgh', '20210429105010_imgAppart.jpg', 10, 1001, '2021-04-29 10:41:46', 0, 2),
(6, 'gffgbsfgbsbgg', 'gergegerger', 'rthrthr', 'defaultAppartementPicture.jpg', 15, 1500, '2021-04-29 11:48:44', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `t_appartementswishlist`
--

CREATE TABLE `t_appartementswishlist` (
  `idUser` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `appVisited` tinyint(1) DEFAULT '0',
  `appRated` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_appartementswishlist`
--

INSERT INTO `t_appartementswishlist` (`idUser`, `idAppartement`, `appVisited`, `appRated`) VALUES
(2, 1, 0, 0),
(2, 3, 0, 1),
(3, 1, 0, 1),
(3, 2, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_profile`
--

CREATE TABLE `t_profile` (
  `idProfile` bigint(20) UNSIGNED NOT NULL,
  `proName` varchar(50) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_profile`
--

INSERT INTO `t_profile` (`idProfile`, `proName`) VALUES
(1, 'dark'),
(2, 'light'),
(3, 'primary'),
(4, 'success'),
(5, 'danger'),
(6, 'warning'),
(7, 'info');

-- --------------------------------------------------------

--
-- Structure de la table `t_rating`
--

CREATE TABLE `t_rating` (
  `idUser` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_rating`
--

INSERT INTO `t_rating` (`idUser`, `idAppartement`) VALUES
(2, 2),
(2, 3),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `idUser` bigint(20) UNSIGNED NOT NULL,
  `usePseudo` varchar(50) NOT NULL,
  `useFirstname` varchar(50) DEFAULT NULL,
  `useName` varchar(50) DEFAULT NULL,
  `usePassword` varchar(255) NOT NULL,
  `useMail` varchar(50) DEFAULT NULL,
  `usePhone` varchar(20) DEFAULT NULL,
  `useImage` varchar(255) DEFAULT 'defaultUserPicture.png	',
  `useProfilePref` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_user`
--

INSERT INTO `t_user` (`idUser`, `usePseudo`, `useFirstname`, `useName`, `usePassword`, `useMail`, `usePhone`, `useImage`, `useProfilePref`) VALUES
(2, 'admin', '', '', '$2y$10$mNdPUfVHLknw79ZtREtSqeEVBfkgjBXeipvSOr.ZnaEqcRChDgDWS', '', '', '20210103123527_feu.gif', 1),
(3, 'test', 'testo', 'test', '$2y$10$W5zkzbeWEQVvFvsKFSGAHuijP.c0.jXxI9hXwjhT/SCeIEZqfYxui', '', '', '20210429144532_chat.gif', 4);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `t_appartement`
--
ALTER TABLE `t_appartement`
  ADD PRIMARY KEY (`idAppartement`),
  ADD UNIQUE KEY `idAppartement_2` (`idAppartement`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idAppartement` (`idAppartement`);

--
-- Index pour la table `t_appartementswishlist`
--
ALTER TABLE `t_appartementswishlist`
  ADD PRIMARY KEY (`idUser`,`idAppartement`),
  ADD KEY `idUser` (`idUser`,`idAppartement`);

--
-- Index pour la table `t_profile`
--
ALTER TABLE `t_profile`
  ADD PRIMARY KEY (`idProfile`),
  ADD UNIQUE KEY `idProfile` (`idProfile`);

--
-- Index pour la table `t_rating`
--
ALTER TABLE `t_rating`
  ADD PRIMARY KEY (`idUser`,`idAppartement`),
  ADD KEY `idUser` (`idUser`,`idAppartement`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `idUser_2` (`idUser`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `useProfilePref` (`useProfilePref`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `t_appartement`
--
ALTER TABLE `t_appartement`
  MODIFY `idAppartement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `t_profile`
--
ALTER TABLE `t_profile`
  MODIFY `idProfile` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `idUser` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
