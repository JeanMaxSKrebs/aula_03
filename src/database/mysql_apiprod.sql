-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2021 at 09:58 AM
-- Server version: 8.0.27-0ubuntu0.20.04.1
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `produtos_crud_pdo`
--

-- --------------------------------------------------------

--
-- Table structure for table `descontos`
--

CREATE TABLE `descontos` (
  `id_desc` bigint UNSIGNED NOT NULL,
  `descricao` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `taxa` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `descontos`
--

INSERT INTO `descontos` (`id_desc`, `descricao`, `taxa`) VALUES
(5, 'Black Friday', '20.00'),
(6, 'Cyber Monday', '15.00'),
(7, 'Natal', '25.00'),
(8, 'Ano Novo', '22.00'),
(9, 'Carnaval', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `extras`
--

CREATE TABLE `extras` (
  `id_ext` bigint UNSIGNED NOT NULL,
  `descricao` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `extras`
--

INSERT INTO `extras` (`id_ext`, `descricao`) VALUES
(1, 'cumque'),
(2, 'accusamus'),
(3, 'veniam'),
(4, 'sint');

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id_prod` bigint UNSIGNED NOT NULL,
  `nome` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descricao` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `qtd_estoque` int NOT NULL DEFAULT '0',
  `preco` decimal(10,2) NOT NULL,
  `importado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id_prod`, `nome`, `descricao`, `qtd_estoque`, `preco`, `importado`) VALUES
(111, 'Samsumg A5 - 2017', 'Samsumg A5 2017 2GB Exynos 8Core', 2, '4500.00', 0),
(112, 'Notebook DELL Inspiron 15', 'I5 7600HQ 8GBMen GTX1030m SSD 1TB', 300, '8500.00', 0),
(113, 'Notebook Samsumg Gamer', 'I7 10800HQ 16GB MEM NVIDIA-RTX2060m SSD 2TB', 150, '17500.00', 0),
(114, 'SSD 4TB', 'SSD SAMSUMG EVO 860 4TB', 200, '5750.00', 0),
(115, 'SSD 2TB', 'SSD SAMSUMG EVO 860 2TB', 150, '3750.00', 0),
(121, 'SSD 4TB', 'SSD WESTERN DIGITAL', 50, '4150.00', 0),
(122, 'GAINWARD PHOENIX RTX3080ti', 'GPU NVIDIA 12GB MEM GDDR6 256BITS GAINWARD PHOENIX ', 30, '14150.00', 0),
(123, 'GAINWARD PHOENIX RTX3070', 'GPU NVIDIA 8GB MEM GDDR6 256BITS GAINWARD PHOENIX ', 60, '7399.00', 0),
(124, 'ECHO DOT ALEXA', 'AMAZON ALEX ECHO DOT 3 GEN SMART SPEAKER', 1000, '200.00', 0),
(125, 'Monitor Asus BK 35\'\'', 'LED 35\" 3440x1440 Preto 1 HDMI(v1.4)', 500, '9990.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prod_desc`
--

CREATE TABLE `prod_desc` (
  `id_prod` bigint UNSIGNED NOT NULL,
  `id_desc` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prod_desc`
--

INSERT INTO `prod_desc` (`id_prod`, `id_desc`) VALUES
(122, 5),
(123, 5),
(125, 5),
(124, 6),
(121, 7),
(123, 7),
(124, 7),
(125, 7),
(124, 8),
(123, 9),
(125, 9);

-- --------------------------------------------------------

--
-- Table structure for table `prod_ext`
--

CREATE TABLE `prod_ext` (
  `id_prod` bigint UNSIGNED NOT NULL,
  `id_ext` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `descontos`
--
ALTER TABLE `descontos`
  ADD PRIMARY KEY (`id_desc`);

--
-- Indexes for table `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id_ext`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_prod`);

--
-- Indexes for table `prod_desc`
--
ALTER TABLE `prod_desc`
  ADD PRIMARY KEY (`id_prod`,`id_desc`),
  ADD KEY `id_desc` (`id_desc`);

--
-- Indexes for table `prod_ext`
--
ALTER TABLE `prod_ext`
  ADD PRIMARY KEY (`id_prod`,`id_ext`),
  ADD KEY `id_ext` (`id_ext`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `descontos`
--
ALTER TABLE `descontos`
  MODIFY `id_desc` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `id_ext` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_prod` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prod_desc`
--
ALTER TABLE `prod_desc`
  ADD CONSTRAINT `prod_desc_ibfk_1` FOREIGN KEY (`id_prod`) REFERENCES `produtos` (`id_prod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prod_desc_ibfk_2` FOREIGN KEY (`id_desc`) REFERENCES `descontos` (`id_desc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prod_ext`
--
ALTER TABLE `prod_ext`
  ADD CONSTRAINT `prod_ext_ibfk_1` FOREIGN KEY (`id_prod`) REFERENCES `produtos` (`id_prod`),
  ADD CONSTRAINT `prod_ext_ibfk_2` FOREIGN KEY (`id_ext`) REFERENCES `extras` (`id_ext`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
