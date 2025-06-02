-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2025 at 02:36 PM
-- Server version: 11.8.1-MariaDB-4
-- PHP Version: 8.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CambridgeDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'mbarukhemedy50@gmail.com', 'c9f49c64e1db819336a5c30c32d75f0300e72770', '2025-05-30 21:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'mbarouk hemed', 'mbarukhemedy50@gmail.com', '\"Sustainable fashion that doesn\'t compromise on design. Their pieces are timeless and comfortable.\"', '2025-06-01 11:12:01'),
(2, 'juma', 'juma@gmail.com', '\"Absolutely love the quality and style! Cambridge has truly elevated my wardrobe. Highly recommend!\"', '2025-06-01 11:12:59'),
(3, 'mwajuma', 'mwajuma45@gmail.com', '\"The customer service is fantastic, and the clothes are even better in person. My new favorite brand!\"', '2025-06-01 11:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `sizes` varchar(100) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `sizes`, `colors`, `image_path`, `created_at`) VALUES
(1, 'Formal Trousers', 'Available All size welcome', 20000.00, 'Formal Trousers', 'S,M,L,XL,XXL', 'Black,White,Blue', 'images/products/683a2fd265ae8.jpg', '2025-05-30 22:23:14'),
(2, 'T-shirts', 'for men and women ', 20000.00, 'T-Shirts', 'S,M,L,XL,XXL', 'Black,White,Blue,Red,Green,Gray', 'images/products/683a33591ca01.jpg', '2025-05-30 22:38:17'),
(3, 'Formal shirts', 'Available all size', 20000.00, 'Formal Shirts', 'S,M,L,XL,XXL', 'Black,White,Blue,Red,Green,Gray', 'images/products/683a33eda8cbe.jpg', '2025-05-30 22:40:45'),
(4, 'Casual Shirts', 'Available in all size', 20000.00, 'Casual Shirts', 'S,M,L,XL,XXL', 'Black,White,Blue,Red,Green,Gray', 'images/products/683a342ecd87e.jpg', '2025-05-30 22:41:50'),
(5, 'jacket', 'Available in all size', 20000.00, 'Jacket', 'S,M,L,XL,XXL', 'Black,White,Blue,Red,Green,Gray', 'images/products/683a35578e278.jpeg', '2025-05-30 22:46:47'),
(7, 'tai', 'available in all size', 20000.00, 'others', 'S,M,L,XL,XXL', 'Black,White,Blue,Red,Green,Gray', 'images/products/683a362b3f81d.jpeg', '2025-05-30 22:50:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
