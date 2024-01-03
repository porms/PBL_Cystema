-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 26, 2023 at 11:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cystema`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_description` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `activity_type`, `activity_description`, `timestamp`) VALUES
(1, 'Product Upload', 'New product uploaded: Diamond Shirt1', '2023-11-29 09:41:46'),
(2, 'Registration Failed', 'User registration failed for solivenf362@gmail.com. Email already exists.', '2023-11-29 09:56:38'),
(3, 'Product Upload', 'New product uploaded: Diamond Tote Bag', '2023-12-01 07:58:41'),
(4, 'Product Upload', 'New product uploaded: Diamond Tote Bag', '2023-12-01 08:28:02'),
(5, 'Product Upload', 'New product uploaded: JWUP Shirt', '2023-12-01 16:27:08'),
(6, 'Product Upload', 'New product uploaded: Diamond Shirt', '2023-12-01 16:28:01'),
(7, 'Product Upload', 'New product uploaded: Skull Coin Purse', '2023-12-01 16:29:50'),
(8, 'Product Upload', 'New product uploaded: Net Cap Graffiti', '2023-12-01 16:30:29'),
(9, 'Product Upload', 'New product uploaded: Tote Bag Splash', '2023-12-03 04:57:15'),
(10, 'Product Updated', 'Updated Product: Diamond Coin Purse', '2023-12-03 05:11:17'),
(11, 'Product Upload', 'New product uploaded: Sublimation OG', '2023-12-03 05:17:57'),
(12, 'Product Upload', 'New product uploaded: CYSTM V2', '2023-12-03 05:18:42'),
(13, 'Product Upload', 'New product uploaded: CYSTM v2.1', '2023-12-03 05:20:29'),
(14, 'Product Upload', 'New product uploaded: Skull Mask', '2023-12-03 05:23:14'),
(15, 'Product Upload', 'New product uploaded: Break Free Black', '2023-12-10 07:25:56'),
(16, 'Product Updated', 'Updated Product: ', '2023-12-23 06:52:10'),
(17, 'Product Updated', 'Updated Product: ', '2023-12-23 06:53:38'),
(18, 'Product Updated', 'Updated Product: ', '2023-12-23 06:54:49'),
(19, 'Product Upload', 'New product uploaded: Net Cap', '2023-12-23 06:56:33');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_flag` tinyint(4) NOT NULL DEFAULT 1,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `admin_flag`, `status`) VALUES
(2, 'pblcystema@gmail.com', 'cystemapblgroup7', 1, 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_image` varchar(45) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` float(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `sub_total` float(10,2) NOT NULL,
  `grand_total` float(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `product_image`, `product_name`, `product_price`, `quantity`, `user_email`, `sub_total`, `grand_total`, `created_at`, `updated_at`) VALUES
(1330, 66, 'Break Free Black.jpg', 'Break Free Black', 1400.00, 1, 'princesslara.coquilla@gmail.com', 0.00, 2300.00, '2023-12-20 04:26:21', '2023-12-20 04:26:21'),
(1346, 57, 'JWUP SHIRT.jpg', 'JWUP Shirt', 400.00, 1, 'japorms.charles.yeah12@gmail.com', 0.00, 400.00, '2023-12-20 05:30:51', '2023-12-20 05:30:51'),
(1347, 58, 'Diamond Shirt.jpg', 'Diamond Shirt', 400.00, 1, 'solivenf362@gmail.com', 0.00, 400.00, '2023-12-26 09:47:01', '2023-12-26 09:47:01');

-- --------------------------------------------------------

--
-- Table structure for table `change_address`
--

CREATE TABLE `change_address` (
  `name` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `contact_number` varchar(45) NOT NULL,
  `region` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Customers_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `address` varchar(45) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(45) DEFAULT NULL,
  `registration_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_flag` tinyint(4) NOT NULL DEFAULT 0,
  `otp` varchar(15) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`Customers_id`, `first_name`, `last_name`, `address`, `username`, `email`, `password`, `contact_number`, `registration_time`, `admin_flag`, `otp`, `status`) VALUES
(18, 'Francis Karl', 'Soliven', 'blkqwe12', 'K11935564', 'solivenf362@gmail.com', '$2y$10$V2MM2KAXV5oKNLxrTYwr8.mdHut6rmoylwnCGzo3yMfvdvnoiX30S', '09682481298', '2023-12-17 17:29:28', 0, '299271', 'verified'),
(20, 'chaw', 'chaww', 'blkqwe121', 'asda', 'japorms.charles.yeah12@gmail.com', '$2y$10$0pFY0cXPTgIX4Q0DslcLa.e/ltuwWM1M.NBtsbF3Y1WywakjltPEK', '1230921', '2023-12-20 10:19:38', 0, '258479', 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `deliveries_id` int(11) NOT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `freedom_wall`
--

CREATE TABLE `freedom_wall` (
  `message` text NOT NULL,
  `design` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `freedom_wall`
--

INSERT INTO `freedom_wall` (`message`, `design`) VALUES
('jhkjhjjk', 'uploads/Blog 5.jpg'),
('asdadaacv', 'uploads/Lomboy.jpg'),
('dasdadacccccc', 'uploads/robert-lukeman-_RBcxo9AU-U-unsplash.jpg'),
('cw212adasxx', 'uploads/Logo Big.jpg'),
('blqkeoalk ', 'uploads/Lomboy.jpg'),
('try try try', 'uploads/Cystema Coin Purse.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(45) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category`, `product_name`, `image`, `price`, `quantity`, `description`, `upload_date`) VALUES
(56, 'Bags', 'Diamond Tote Bag', 'Tote Bag Diamond.jpg', 400, 10, 'Tote Bag', '2023-12-10 07:40:22'),
(57, 'Shirts', 'JWUP Shirt', 'JWUP SHIRT.jpg', 400, 10, '????? - ????', '2023-12-10 07:40:22'),
(58, 'Shirts', 'Diamond Shirt', 'Diamond Shirt.jpg', 400, 10, 'Diamond Shirt Oversized Tee', '2023-12-10 07:40:22'),
(59, 'Accessories', 'Skull Coin Purse', 'Cystema Skull Coin Purse.jpg', 100, 10, 'Coin Purse', '2023-12-10 07:40:22'),
(60, 'Accessories', 'Net Cap Graffiti', 'Net Cap Diamond.jpg', 350, 10, ' Graffiti', '2023-12-10 07:40:22'),
(61, 'Bags', 'Tote Bag Splash', 'Tote Bag Splash.jpg', 800, 23, 'Splash Acrylic Paint', '2023-12-10 07:40:22'),
(62, 'Accessories', 'Sublimation OG', 'Sublimation OG.jpg', 120, 10, 'Cotton Face Mask', '2023-12-10 07:40:22'),
(63, 'Accessories', 'CYSTM V2', 'CYSTM Version 2.jpg', 150, 5, 'Cotton Face Mask', '2023-12-10 07:40:22'),
(64, 'Accessories', 'CYSTM v2.1', 'CYSTM Version 2_1.jpg', 150, 25, 'Cotton Face Mask ', '2023-12-10 07:40:22'),
(65, 'Accessories', 'Skull Mask', 'Skull Mask.jpg', 180, 9, 'Cotton Face Mask', '2023-12-10 07:40:22'),
(66, 'Shirts', 'Break Free Black', 'Break Free Black.jpg', 1400, 12, 'Break Free Black Tshirt', '2023-12-10 07:40:22'),
(67, 'Accessories', 'Net Cap', 'Net Cap Diamond.jpg', 150, 10, 'Net Cap Embroidered design', '2023-12-23 06:56:33');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `day` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `reminder_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_order`
--

CREATE TABLE `shopping_order` (
  `order_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `contact_number` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `method` varchar(45) NOT NULL,
  `street` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `post_code` int(11) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `price_total` float(65,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `customer` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_email`, `activity_type`, `activity_description`, `timestamp`) VALUES
(1, 'japorms.charles.yeah12@gmail.com', 'Product Upload', 'New product uploaded: ', '2023-12-20 05:02:57'),
(2, 'japorms.charles.yeah12@gmail.com', 'Add To Cart', 'Item Added to Cart: ', '2023-12-20 05:30:51'),
(3, 'solivenf362@gmail.com', 'Add To Cart', 'Item Added to Cart: ', '2023-12-26 09:47:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Customers_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`deliveries_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopping_order`
--
ALTER TABLE `shopping_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `customer` (`customer`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1348;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Customers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `deliveries_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_order`
--
ALTER TABLE `shopping_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `customers` (`Customers_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `customers` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
