-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2023 at 08:02 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kapetann`
--

CREATE DATABASE `kapetann`;
USE `kapetann`;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `title` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal_amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `activated` bit(1) DEFAULT (false),
  `activation_token` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `phonenumber` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reset_password` (
  `email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expire_on` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `img` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL default 'coffee',
  `quantity` int(11) NOT NULL default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cart` (
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `purchase_history` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal_amount` decimal(10,2) NOT NULL,
  `created_date` datetime NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `stars` int(11) NOT NULL DEFAULT 0,
  `review` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phonenumber` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `applications` (
  `id`  varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phonenumber` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` varchar(100) NOT NULL,
  `product` varchar(100) NOT NULL,
  `application_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `price`, `title`, `quantity`, `subtotal_amount`, `date`, `invoice_number`, `user_id`) VALUES
(1, '40.00', 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, '40.00', '2023-04-21', 'INV-760084', 0),
(2, '45.00', 'AMERICANO - HOT ESPRESSO (12 OZ)', 1, '45.00', '2023-04-21', 'INV-760084', 0),
(3, '40.00', 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, '40.00', '2023-04-21', 'INV-174394', 2),
(4, '50.00', 'NITRO COLD BREW W/ STRAW (12 OZ)', 1, '50.00', '2023-04-21', 'INV-741371', 2),
(5, '45.00', 'AMERICANO - HOT ESPRESSO (12 OZ)', 1, '45.00', '2023-04-21', 'INV-982020', 2),
(6, '40.00', 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, '40.00', '2023-04-21', 'INV-144116', 2);


INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `create_datetime`, `activated`, `activation_token`) VALUES
(1, 'John Rovie', 'RovicBalingbing', 'balingbing.johnrovie20@gmail.com', '850f5f5611e06993cc07363c98c560d0', '2023-04-18 08:59:41', b'1', 'activation_token'),
(2, 'admin', 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2023-04-18 11:00:40', b'1', 'activation_token'),
(3, 'sample', 'sample', 'sample', '5e8ff9bf55ba3508199d22e984129be6', '2023-04-18 11:03:23', b'1', 'activation_token'),
(4, 'Rovic', 'Rovic', 'Rovic@gmail.com', '6bafff124175b93f6358d465c5a654d9', '2023-04-19 12:14:34', b'1', 'activation_token');


INSERT INTO `products` (`id`, `title`, `img`, `price`) VALUES
(1, 'Americano - Hot Espresso (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-1.png', '4.50'),
(2, 'Colombian Supremo Cup (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-2.png', '4.00'),
(3, 'Nitro Cold Brew w/ Straw (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-3.png', '5.00'),
(4, 'Seasonal Single-Origin (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-4.png', '3.00'),
(5, 'Indonesian Sumatra Mandheling (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-5.png', '4.00'),
(6, 'Mint Mojito Iced Coffee (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-6.png', '5.50'),
(7, 'Iced Americano (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-7.png', '3.50'),
(8, 'Specialty Brews (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-8.png', '8.50'),
(9, 'Seasonal Origin (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-9.png', '8.00'),
(10, 'Ethiopian Yirgacheffe Cup (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-10.png', '5.50'),
(11, 'Cold Brew Tonic In a Cup (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-11.png', '3.50'),
(12, 'Caramel Cold Foam Cold Brew (12 OZ)', 'http://localhost/coffee-shop-website/assets/images/cart-item-12.png', '5.50');

INSERT INTO `products` (`id`, `title`, `img`, `price`, `category`) VALUES
(13, 'NINJA CFN601 ESPRESSO', 'http://localhost/coffee-shop-website/assets/images/cart-item-13.png', '200.00', 'machine'),
(14, 'BLACK+DECKER', 'http://localhost/coffee-shop-website/assets/images/cart-item-14.png', '155.00', 'machine'),
(15, 'CASABREWS ESPRESSO', 'http://localhost/coffee-shop-website/assets/images/cart-item-15.png', '125.00', 'machine');

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`email`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
