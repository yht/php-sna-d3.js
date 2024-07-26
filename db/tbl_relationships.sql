-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2024 at 06:02 AM
-- Server version: 10.11.6-MariaDB-0+deb12u1
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `sna`
--

-- --------------------------------------------------------

--
-- Table structure for table `entity_relationships`
--

CREATE TABLE `entity_relationships` (
  `entity_id` int(11) DEFAULT NULL,
  `related_entity_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entity_relationships`
--
ALTER TABLE `entity_relationships`
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `related_entity_id` (`related_entity_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entity_relationships`
--
ALTER TABLE `entity_relationships`
  ADD CONSTRAINT `entity_relationships_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`),
  ADD CONSTRAINT `entity_relationships_ibfk_2` FOREIGN KEY (`related_entity_id`) REFERENCES `entities` (`id`);
COMMIT;
