-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 09:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `return_date` date NOT NULL,
  `return_time` time NOT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_location` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `expiry` varchar(10) DEFAULT NULL,
  `cvv` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `pickup_date`, `pickup_time`, `return_date`, `return_time`, `payment_status`, `created_at`, `pickup_location`, `payment_method`, `card_number`, `expiry`, `cvv`) VALUES
(1, 4, 1, '2025-07-03', '08:00:00', '2025-07-03', '10:00:00', 'Pending', '2025-07-02 15:15:11', 'Johor Bahru', NULL, NULL, NULL, NULL),
(2, 4, 1, '2025-07-03', '08:00:00', '2025-07-03', '10:00:00', 'Pending', '2025-07-02 15:31:35', 'Johor Bahru', NULL, NULL, NULL, NULL),
(3, 4, 1, '2025-07-03', '09:45:00', '2025-07-03', '12:45:00', 'Pending', '2025-07-02 15:45:08', 'Johor Bahru', NULL, NULL, NULL, NULL),
(4, 4, 1, '2025-07-04', '15:30:00', '2025-07-04', '20:30:00', 'Pending', '2025-07-02 17:27:37', 'Johor Bahru', NULL, NULL, NULL, NULL),
(5, 4, 4, '2025-07-04', '14:30:00', '2025-07-04', '19:30:00', 'paid', '2025-07-02 18:23:45', 'Muar ', 'Credit Card', NULL, NULL, NULL),
(6, 4, 6, '2025-07-04', '14:30:00', '2025-07-04', '20:30:00', 'paid', '2025-07-02 18:32:49', 'Southkey', 'Credit Card', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL COMMENT 'Auto-incrementing ID',
  `name` varchar(100) NOT NULL COMMENT 'Branch Name',
  `address` varchar(255) NOT NULL COMMENT 'Full address',
  `latitude` double NOT NULL COMMENT 'GPS Latitude',
  `longitude` double NOT NULL COMMENT 'GPS longitude'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `latitude`, `longitude`) VALUES
(1, 'Johor Bahru', 'Lot G03, Komtar JBCC, Jalan Wong Ah Fook, 80000 Johor Bahru, Johor', 1.4591, 103.7625),
(2, 'City Square', '108, Jalan Wong Ah Fook, 80000 Johor Bahru, Johor', 1.4628, 103.7621),
(3, 'Southkey', 'The Mall, Mid Valley Southkey, 1 Persiaran Southkey 1, 80150 Johor Bahru, Johor', 1.4938, 103.7753),
(4, 'Taman Pelangi', '21, Jalan Kuning, Taman Pelangi, 80400 Johor Bahru, Johor', 1.4817, 103.7694),
(5, 'Larkin Sentral', 'Jalan Garuda, Larkin Jaya, 80350 Johor Bahru, Johor', 1.4961, 103.7408),
(6, 'Skudai ', 'No. 1, Jalan Pendidikan 8, Taman Universiti, 81300 Skudai, Johor', 1.5372, 103.6325),
(7, 'Taman Sutera', '15, Jalan Sutera Tanjung 8/2, Taman Sutera Utama, 81300 Skudai, Johor', 1.5215, 103.6681),
(8, 'Iskandar Puteri', 'Medini 6, Lebuh Medini Utara, Iskandar Puteri, 79250 Nusajaya, Johor', 1.4243, 103.6354),
(9, 'Bukit Indah', 'AEON Bukit Indah, Jalan Indah 15/2, Taman Bukit Indah, 81200 Johor Bahru, Johor', 1.4813, 103.6543),
(10, 'Pasir Gudang ', 'Persiaran Dahlia 2, Taman Bukit Dahlia, 81700 Pasir Gudang, Johor', 1.4748, 103.9043),
(11, 'Seri Alam', 'Jalan Suria 19, Bandar Seri Alam, 81750 Masai, Johor', 1.5198, 103.9093),
(12, 'Muar ', 'No. 18, Jalan Maharani, Bandar Maharani, 84000 Muar, Johor', 2.0482, 102.5689),
(13, 'Bakri', 'Jalan Bakri, Taman Bakri Jaya, 84200 Muar, Johor', 2.0627, 102.6198),
(14, 'Batu Pahat ', '5, Jalan Zabedah, Kampung Pegawai, 83000 Batu Pahat, Johor', 1.8516, 102.9325),
(15, 'Parit Raja', 'Jalan Universiti, Parit Raja, 86400 Batu Pahat, Johor', 1.8696, 103.0849),
(16, 'Kluang ', '19, Jalan Dato Teoh Siew Khor, 86000 Kluang, Johor', 2.0324, 103.3185),
(17, 'Taman Sri Kluang', 'Jalan Rambutan, Taman Sri Kluang, 86000 Kluang, Johor', 2.0256, 103.3327),
(18, 'Pontian ', 'No. 23, Jalan Bakek, 82000 Pontian, Johor', 1.4891, 103.3894),
(19, 'Segamat ', '123, Jalan Genuang, Taman Yayasan, 85000 Segamat, Johor', 2.5274, 102.8158),
(20, 'Segamat Baru', 'Jalan Hassan, Bandar Segamat Baru, 85000 Segamat, Johor', 2.5226, 102.8137),
(21, 'Kota Tinggi', 'Jalan Niaga 1, Pusat Perdagangan Kota Tinggi, 81900 Kota Tinggi, Johor', 1.7386, 103.8992),
(22, 'Tangkak', 'No. 2, Jalan Payamas, 84900 Tangkak, Johor', 2.2689, 102.5441),
(23, 'Yong Peng', 'Jalan Besar, 83700 Yong Peng, Johor', 2.0111, 103.0601),
(24, 'Simpang Renggam', 'Jalan Besar, 86200 Simpang Renggam, Johor', 1.8614, 103.3036),
(25, 'Ayer Hitam', 'Jalan Utama, 86100 Ayer Hitam, Johor', 1.9333, 103.1);

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_hour` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `name`, `description`, `price_per_hour`, `image`, `branch_id`) VALUES
(1, 'Toyota', 'Toyota Camry 2022', 'Comfortable sedan', 15.00, 'camry.jpg', 1),
(2, 'Toyota', 'Toyota Vios 2021', 'Compact and fuel efficient', 15.00, 'vios.jpg', 4),
(3, 'Honda', 'Honda Civic 2023', 'Stylish compact sedan', 20.00, 'civic.jpg', 6),
(4, 'Honda', 'Honda Accord 2021', 'Spacious midsize sedan', 20.00, 'accord.jpeg', 3),
(5, 'Perodua', 'Perodua Myvi 2022', 'Popular Malaysian hatchback', 10.00, 'myvi.jpg', 1),
(6, 'Proton', 'Proton X70 2022', 'Modern SUV with advanced safety', 12.00, 'x70.jpg', 1),
(7, 'Proton', 'Proton Saga 2021', 'Affordable compact sedan', 10.00, 'saga.jpeg', 1),
(8, 'Ford', 'Ford Mustang 2023', 'Powerful sports car', 90.00, 'mustang.jpeg', 1),
(9, 'Ford', 'Ford Ranger 2022', 'Durable pickup truck', 80.00, 'ford-ranger.jpeg', 1),
(10, 'BMW', 'BMW X5 2022', 'Luxury SUV with premium features', 70.00, 'bmw-x5.jpeg', 1),
(11, 'BMW', 'BMW 3 Series 2021', 'Sporty luxury sedan', 90.00, '3series.jpeg', 1),
(12, 'Perodua', 'Perodua Axia 2023', 'Affordable and compact city car', 10.00, 'axia.jpg', 2),
(13, 'Perodua', 'Perodua Alza 2023', 'Spacious MPV suitable for families', 12.00, 'alza.jpg', 5),
(14, 'Perodua', 'Perodua Aruz 2022', 'Versatile and stylish SUV', 12.00, 'aruz.jpg', 3),
(15, 'Proton', 'Proton X50 2023', 'Modern crossover with smart features', 15.00, 'x50.jpg', 1),
(16, 'Proton', 'Proton S70 2024', 'Elegant and spacious sedan', 15.00, 's70.jpg', 4),
(17, 'Toyota', 'Toyota Hilux 2023', 'Rugged and reliable pickup truck', 30.00, 'hilux.jpg', 18),
(18, 'Toyota', 'Toyota Corolla 2023', 'Smart, safe, and efficient sedan', 20.00, 'corolla.jpg', 10),
(19, 'Toyota', 'Toyota Vellfire 2022', 'Luxury MPV for executive comfort', 70.00, 'vellfire.jpg', 20),
(20, 'Toyota', 'Toyota Alphard 2023', 'Premium MPV with ultimate luxury', 70.00, 'alphard.jpg', 19),
(21, 'Toyota', 'Toyota Yaris 2022', '', 30.00, 'yaris.jpg', 22),
(22, 'Honda', 'Honda City 2023', 'Efficient and elegant city sedan', 25.00, 'city.jpg', 23),
(23, 'Honda', 'Honda HR-V 2023', 'Crossover with sporty style', 30.00, 'hrv.jpg', 24),
(24, 'Honda', 'Honda CR-V 2023', 'Spacious and powerful SUV', 30.00, 'crv.jpg', 25),
(25, 'Honda', 'Honda Jazz 2021', 'Versatile and youthful compact car', 15.00, 'jazz.jpg', 21),
(26, 'Mercedes', 'Mercedes-Benz C-Class 2023', 'Luxury compact executive sedan', 100.00, 'c-class.jpg', 5),
(27, 'Mercedes', 'Mercedes-Benz GLC 2023', 'Luxury midsize SUV with performance', 150.00, 'glc.jpg', 8),
(28, 'Mercedes', 'Mercedes-Benz A-Class 2023', 'Entry-level premium hatchback', 120.00, 'a-class.jpg', 12),
(29, 'Mazda', 'Mazda CX-5 2023', 'Sleek and sporty compact SUV', 65.00, 'cx5.jpg', 6),
(30, 'Mazda', 'Mazda 3 Sedan 2023', 'Elegant and dynamic sedan', 60.00, 'mazda3.jpg', 7),
(31, 'Mazda', 'Mazda CX-30 2023', 'Sporty crossover with premium feel', 50.00, 'cx-30.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `cars_backup`
--

CREATE TABLE `cars_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `brand` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars_backup`
--

INSERT INTO `cars_backup` (`id`, `brand`, `name`, `description`, `price_per_day`, `image`, `branch_id`) VALUES
(1, 'Toyota', 'Toyota Camry 2022', 'Comfortable sedan', 70.00, 'camry.jpg', 1),
(2, 'Toyota', 'Toyota Vios 2021', 'Compact and fuel efficient', 65.00, 'vios.jpg', 4),
(3, 'Honda', 'Honda Civic 2023', 'Stylish compact sedan', 80.00, 'civic.jpg', 6),
(4, 'Honda', 'Honda Accord 2021', 'Spacious midsize sedan', 90.00, 'accord.jpeg', 3),
(5, 'Perodua', 'Perodua Myvi 2022', 'Popular Malaysian hatchback', 40.00, 'myvi.jpg', 1),
(6, 'Proton', 'Proton X70 2022', 'Modern SUV with advanced safety', 60.00, 'x70.jpg', 1),
(7, 'Proton', 'Proton Saga 2021', 'Affordable compact sedan', 50.00, 'saga.jpeg', 1),
(8, 'Ford', 'Ford Mustang 2023', 'Powerful sports car', 300.00, 'mustang.jpeg', 1),
(9, 'Ford', 'Ford Ranger 2022', 'Durable pickup truck', 200.00, 'ford-ranger.jpeg', 1),
(10, 'BMW', 'BMW X5 2022', 'Luxury SUV with premium features', 250.00, 'bmw-x5.jpeg', 1),
(11, 'BMW', 'BMW 3 Series 2021', 'Sporty luxury sedan', 280.00, '3series.jpeg', 1),
(12, 'Perodua', 'Perodua Axia 2023', 'Affordable and compact city car', 35.00, 'axia.jpg', 2),
(13, 'Perodua', 'Perodua Alza 2023', 'Spacious MPV suitable for families', 55.00, 'alza.jpg', 5),
(14, 'Perodua', 'Perodua Aruz 2022', 'Versatile and stylish SUV', 65.00, 'aruz.jpg', 3),
(15, 'Proton', 'Proton X50 2023', 'Modern crossover with smart features', 70.00, 'x50.jpg', 1),
(16, 'Proton', 'Proton S70 2024', 'Elegant and spacious sedan', 75.00, 's70.jpg', 4),
(17, 'Toyota', 'Toyota Hilux 2023', 'Rugged and reliable pickup truck', 150.00, 'hilux.jpg', 18),
(18, 'Toyota', 'Toyota Corolla 2023', 'Smart, safe, and efficient sedan', 85.00, 'corolla.jpg', 10),
(19, 'Toyota', 'Toyota Vellfire 2022', 'Luxury MPV for executive comfort', 320.00, 'vellfire.jpg', 20),
(20, 'Toyota', 'Toyota Alphard 2023', 'Premium MPV with ultimate luxury', 350.00, 'alphard.jpg', 19),
(21, 'Toyota', 'Toyota Yaris 2022', '', 60.00, 'yaris.jpg', 22),
(22, 'Honda', 'Honda City 2023', 'Efficient and elegant city sedan', 70.00, 'city.jpg', 23),
(23, 'Honda', 'Honda HR-V 2023', 'Crossover with sporty style', 95.00, 'hrv.jpg', 24),
(24, 'Honda', 'Honda CR-V 2023', 'Spacious and powerful SUV', 110.00, 'crv.jpg', 25),
(25, 'Honda', 'Honda Jazz 2021', 'Versatile and youthful compact car', 55.00, 'jazz.jpg', 21),
(26, 'Mercedes', 'Mercedes-Benz C-Class 2023', 'Luxury compact executive sedan', 280.00, 'c-class.jpg', 5),
(27, 'Mercedes', 'Mercedes-Benz GLC 2023', 'Luxury midsize SUV with performance', 350.00, 'glc.jpg', 8),
(28, 'Mercedes', 'Mercedes-Benz A-Class 2023', 'Entry-level premium hatchback', 250.00, 'a-class.jpg', 12),
(29, 'Mazda', 'Mazda CX-5 2023', 'Sleek and sporty compact SUV', 190.00, 'cx5.jpg', 6),
(30, 'Mazda', 'Mazda 3 Sedan 2023', 'Elegant and dynamic sedan', 160.00, 'mazda3.jpg', 7),
(31, 'Mazda', 'Mazda CX-30 2023', 'Sporty crossover with premium feel', 175.00, 'cx-30.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `car_branches`
--

CREATE TABLE `car_branches` (
  `car_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_branches`
--

INSERT INTO `car_branches` (`car_id`, `branch_id`) VALUES
(1, 1),
(1, 6),
(1, 14),
(2, 4),
(2, 7),
(2, 11),
(3, 9),
(3, 2),
(3, 16),
(4, 12),
(4, 5),
(4, 21),
(5, 8),
(5, 3),
(5, 13),
(6, 3),
(6, 10),
(6, 17),
(7, 2),
(7, 15),
(7, 18),
(8, 7),
(8, 1),
(8, 12),
(9, 15),
(9, 8),
(9, 22),
(10, 5),
(10, 9),
(10, 19),
(11, 6),
(11, 11),
(11, 20),
(12, 11),
(12, 4),
(12, 17),
(13, 16),
(13, 8),
(13, 23),
(14, 13),
(14, 1),
(14, 24),
(15, 14),
(15, 7),
(15, 25),
(16, 17),
(16, 5),
(16, 22),
(17, 18),
(17, 3),
(17, 12),
(18, 10),
(18, 6),
(18, 21),
(19, 20),
(19, 9),
(19, 15),
(20, 19),
(20, 2),
(20, 23),
(21, 22),
(21, 13),
(21, 4),
(22, 23),
(22, 11),
(22, 16),
(23, 24),
(23, 8),
(23, 17),
(24, 25),
(24, 14),
(24, 19),
(25, 21),
(25, 5),
(25, 18),
(26, 5),
(26, 9),
(26, 12),
(27, 8),
(27, 2),
(27, 14),
(28, 12),
(28, 7),
(28, 20),
(29, 6),
(29, 11),
(29, 19),
(30, 7),
(30, 13),
(30, 21),
(31, 4),
(31, 9),
(31, 22),
(32, 9),
(32, 15),
(32, 25);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `phone_number`, `address`, `license_number`, `profile_picture`, `phone`) VALUES
(2, 'Darshni', 'darshniprakash3@gmail.com', '$2y$10$m6czi1kkP1edVeU0KqyOJOy1MaZ1dWj38Y3lhNJBiuUC3hV0L8H/i', 'user', '', '', '', '6864a240e57e9_ferrari.jpg', '0109023197'),
(3, 'Lily', 'lily@gmail.com', '$2y$10$uUfwWILOaNMsVe0Gq4qfl.wfLmymi.uiNOWgSewV04tatAFymfFoq', 'user', NULL, NULL, NULL, NULL, NULL),
(4, 'Lim', 'lim@gmail.com', '$2y$10$ioFJq7ygy2iHLYNBBVQKne1xpIJxO/qBA7IGa3bpIDPWKQEATZE2y', 'user', NULL, NULL, NULL, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing ID', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
