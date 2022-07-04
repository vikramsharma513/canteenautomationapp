-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2022 at 05:47 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canteen_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL COMMENT 'It holds the id of category',
  `categoryName` varchar(50) NOT NULL COMMENT 'It holds the name of category',
  `categoryDesc` varchar(255) NOT NULL COMMENT 'It holds the description of category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`, `categoryDesc`) VALUES
(5, 'Healthy', 'For Health conscious'),
(6, 'Burger', 'veg, non-veg'),
(7, 'Soft Drinks', 'cold drinks, juices, Lassi'),
(8, 'Momo', 'steam, fried, sadeko,veg, non-veg'),
(9, 'Pizza', 'veg, non-veg, large, small'),
(10, 'Kati Roll', 'Type: veg, chicken, egg roll\nIngredients: cabbage, capcicum, mayonnaise, ketchup, tomato, roti'),
(24, 'Snacks', 'varieties of food'),
(25, 'Sandwiches', 'Types: chicken,veg or egg sandwich\nIngredients: mayonnaise, cabbage, capcicum, tomato');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerId` int(11) NOT NULL COMMENT 'It holds the id of customer',
  `name` varchar(50) NOT NULL COMMENT 'It holds the name of customer',
  `email` varchar(75) NOT NULL COMMENT 'It holds the email of customer',
  `password` text NOT NULL COMMENT 'It stores the password of customer',
  `phoneNum` varchar(15) NOT NULL COMMENT 'It holds the contact number of customer',
  `apikey` varchar(50) NOT NULL COMMENT 'It holds the api key of customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'It holds the created time and date of customer',
  `profile_pic` varchar(255) NOT NULL DEFAULT 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png' COMMENT 'It holds the image url of customer profile picture'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerId`, `name`, `email`, `password`, `phoneNum`, `apikey`, `created_at`, `profile_pic`) VALUES
(2, 'riya', 'riya@gmail.com', '$2a$10$165b776d3f6b6ae8dca0cu7HkxmahPbbO9O7NpPXbM8IGSuBUQL0u', '9802782321', 'e94b6e06139368709e56571ae38a3feb', '2021-02-18 19:13:25', 'https://www.riyanakarmi.com.np/profilePicture/defaultUser.png'),
(5, 'anita', 'anita@gmail.com', '$2a$10$1b3a02dc0a7b2b79abf2aeuhRNbW5e2r9IA.YkrxRa926A2ImfG96', '9805846549', '248dfb6ba5a41a81ab93708394fba6dd', '2021-03-04 06:39:37', 'https://www.riyanakarmi.com.np/profilePicture/defaultUser.png'),
(6, 'Pranisaa Gurung', 'pranisha@gmail.com', '$2a$10$41e2241d4a6562922878fONtN5mLU.EMzNAEipCcvXKCSCsetL./C', '9804005022', '99f7d45bc46f4b2120b5022db975e249', '2021-03-04 07:03:16', 'https://www.riyanakarmi.com.np/profilePicture/2021/04/IMG_20200926_150727_1617178056862_1618291099_6.jpg'),
(7, 'sameer', 'sameer@gmail.com', '$2a$10$c78d6cebbd71608e0b044uPlrAIE0cYCpJitmddtYRQdNMxLWGWou', '1234567890', 'cb18ccb615d7bbe9bfcef135892f4fb6', '2021-03-05 06:31:22', 'https://www.riyanakarmi.com.np/profilePicture/defaultUser.png'),
(8, 'dhukusha', 'abc@gmail.com', '$2a$10$a5016f7de07b9c300706auFABiIcEALdq46BYGVCZzbpvSlj.GCiO', '9802832441', 'd4166ef6bcc1441af88075124ae67c51', '2021-03-25 12:54:14', 'https://www.riyanakarmi.com.np/profilePicture/defaultUser.png'),
(9, 'User user', 'user@gmail.com', '$2a$10$478ea2c2ded772ee07167uFA77NHV9Vtr5R9yWNO2JFORZDA/C.y2', '9805867687', '2fa6282e918b7babe05e9009bc9ddf08', '2021-04-05 10:44:38', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png'),
(10, 'Riya Nakarmi', 'nakarmiriya@gmail.com', '$2a$10$bfc3f7eae2e0c8ee0b541usvAM5gmDpWnrS5pMJnMMEEReOsSQ0/i', '9804002831', '3db6b5d44808924a5b270cee23d1a376', '2021-04-13 06:47:55', 'https://www.riyanakarmi.com.np/profilePicture/2021/05/IMG_20200807_180113_1621408594_10.jpg'),
(11, 'Sangam Bajimaya', 'sb@gmail.com', '$2a$10$4313b0c66b1c3251d2af1u6n2modQpjpIFAUZHiSYTruytvcWEhdi', '9807255638', '6e3267b48c256e0b7fafef9403aa6377', '2021-04-25 02:35:44', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png'),
(12, 'Rita', 'rita@gmail.com', '$2a$10$224199b54d1105f831b37egO/OIZC8Ma.bcKucjVnLVPsobphofo6', '9856995685', 'c6d5a97fd93d05099710c6cfaf92c56d', '2021-04-26 16:00:41', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png'),
(13, 'usertest', 'test@gmail.com', '$2a$10$6c74cee713a7fac369888OzqexyVqR3euUOghRWVywtYA4oWxgJuq', '9805863742', '3e94a5137e851a7690454407219f4c04', '2021-04-26 16:04:18', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png'),
(14, 'Riya Nakarmi', 'riyanakarmi@gmail.com', '$2a$10$65c44ddaef93dc8687960uWJy8LIjfLxE9wednDDkKOamjwHH2d5W', '9804002831', 'f02dab81ddc69635200b318074e61f46', '2021-04-26 16:05:42', 'https://www.riyanakarmi.com.np/profilePicture/2021/04/images_1619606284_14.png'),
(15, 'fhh', 'vb@vv.vom', '$2a$10$22d97d323ef7743a74efbOOx/0JtIbWkgseCPuzF839EpnrvD1VaS', '6669689668', 'c40dc05a40c0c77e82d2c33b170b176b', '2021-04-28 19:00:56', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png'),
(16, 'test', 'testing@gmail.com', '$2a$10$a9b1b144c131b27e804d9uRav44SxCIP5CqWmXrxuloNSV73rtCXW', '9896576771', '31daa91ba4c6e5f7f66502fe79d96cb3', '2022-03-24 04:17:47', 'https://www.riyanakarmi.com.np/Backend/profilePicture/defaultUser.png');

-- --------------------------------------------------------

--
-- Table structure for table `foodlist`
--

CREATE TABLE `foodlist` (
  `foodId` int(11) NOT NULL COMMENT 'It holds the id of foodlist',
  `foodName` varchar(50) NOT NULL COMMENT 'It holds the name of food item.',
  `foodDesc` varchar(255) NOT NULL COMMENT 'It stores the description of food item',
  `price` int(11) NOT NULL COMMENT 'It holds the unit price of food item.',
  `quantity` int(11) NOT NULL COMMENT 'It stores the total available quantity of food item.',
  `categoryId` int(11) NOT NULL COMMENT 'It is the foreign key of foodlist table ',
  `image_url` varchar(255) NOT NULL COMMENT 'It stores the url of food image'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `foodlist`
--

INSERT INTO `foodlist` (`foodId`, `foodName`, `foodDesc`, `price`, `quantity`, `categoryId`, `image_url`) VALUES
(4, 'Chicken Salad', 'Greek-style salad made with loads fresh vegetables, chicken', 220, 4, 5, 'Backend/adminSection/FoodImage/salad.jpg'),
(6, 'Veg Burger', 'Includes chicken patte, Guacamole, Onion, Lettuce, Tomato, Cheese, Pickle', 340, 5, 6, 'Backend/adminSection/FoodImage/veg-burger.jpg'),
(9, 'Chicken Burger', 'Includes chicken patte, Guacamole, Onion, Lettuce, Tomato, Cheese, Pickle', 200, 7, 6, 'Backend/adminSection/FoodImage/chickenburger.jpg'),
(10, 'Cheese Pizza', 'Includes pepperoni, extra Cheese', 350, 3, 9, 'Backend/adminSection/FoodImage/pizza.png'),
(11, 'Coke ', 'Coke with ice prefect for summer thirst', 50, 100, 7, 'Backend/adminSection/FoodImage/cocacola.jpg'),
(12, 'Real Juice', 'Real juice, mixed fruit juice, prefect for summer thirst', 30, 100, 7, 'Backend/adminSection/FoodImage/Real-Juice.jpg'),
(13, 'Paneer Roll', 'A specialty of paneer with vegetables, cilantro and onion wrapped in a roti roll.', 70, 15, 10, 'Backend/adminSection/FoodImage/kathi-roll_paneer.jpg'),
(14, 'Egg Roll', 'A specialty of egg omelette with vegetables, cilantro and onion wrapped in a roti roll.', 90, 20, 10, 'Backend/adminSection/FoodImage/Egg-Kati-Roll.jpg'),
(15, 'Steam Chicken Momo', 'Nepali style steamed chicken momo', 100, 50, 8, 'Backend/adminSection/FoodImage/chicken-momo.jpg'),
(16, 'Fried Chicken Momo', 'Nepali style fried chicken momo', 120, 30, 8, 'Backend/adminSection/FoodImage/fried-momo.jpg'),
(31, 'French Fry', 'Long, thin and sliced potato made by deep frying them', 70, 40, 24, 'Backend/adminSection/FoodImage/517953.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `foodlist`
--
ALTER TABLE `foodlist`
  ADD PRIMARY KEY (`foodId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'It holds the id of category', AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'It holds the id of customer', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `foodlist`
--
ALTER TABLE `foodlist`
  MODIFY `foodId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'It holds the id of foodlist', AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foodlist`
--
ALTER TABLE `foodlist`
  ADD CONSTRAINT `foodlist_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
