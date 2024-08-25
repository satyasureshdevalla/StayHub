

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Database: `Stay Hub: Your Room Reservation Solution`
--

-- --------------------------------------------------------

--
-- Table structure for table `bedding_master`
--

CREATE TABLE `bedding_master` (
  `bedding_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ratio_with_room` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bedding_master`
--

INSERT INTO `bedding_master` (`bedding_id`, `type`, `ratio_with_room`) VALUES
(1, '-None', '0.00'),
(2, 'Single', '7.00'),
(3, 'Double', '8.00'),
(4, 'Triple', '9.00'),
(5, 'Quad', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `bookings_billing_details`
--

CREATE TABLE `bookings_billing_details` (
  `booking_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bookings_details`
--

CREATE TABLE `bookings_details` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `no_of_adults` int(11) NOT NULL,
  `no_of_childs` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `booking_date` datetime NOT NULL DEFAULT current_timestamp(),
  `net_amount` decimal(10,2) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bookings_room_details`
--

CREATE TABLE `bookings_room_details` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `bedding_id` int(11) NOT NULL,
  `room_price` int(11) NOT NULL,
  `ratio_with_room` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(64) NOT NULL,
  `received_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `image_id` int(11) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `caption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`image_id`, `file_name`, `caption`) VALUES
(1, '1.jpeg', 'This is image 1 Caption'),
(2, '2.jpeg', 'This is image 2 Caption'),
(3, '3.jpeg', 'This is image 3 Caption'),
(4, '4.jpeg', 'This is image 4 Caption'),
(5, '5.jpeg', 'This is image 5 Caption'),
(6, '6.jpeg', 'This is image 6 Caption'),
(7, '7.jpeg', 'This is image 7 Caption'),
(8, '8.jpeg', 'This is image 8 Caption'),
(9, '9.jpeg', 'This is image 9 Caption'),
(10, '10.jpeg', 'This is image 10 Caption');

-- --------------------------------------------------------

--
-- Table structure for table `room_master`
--

CREATE TABLE `room_master` (
  `room_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `maximum_capacity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_master`
--

INSERT INTO `room_master` (`room_id`, `type`, `maximum_capacity`, `price`, `image`) VALUES
(1, 'Deluxe Room', 2, 380, '1.jpeg'),
(2, 'Guest House', 5, 150, '2.jpeg'),
(3, 'Luxury Room', 3, 230, '3.jpeg'),
(4, 'Single Room', 4, 120, '4.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE `user_master` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(64) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `setting_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`setting_id`, `name`, `value`) VALUES
(1, 'WelcomeMessage', 'When you get into a hotel room, you lock the door, and you know there is a secrecy, there is a luxury, there is fantasy. There is comfort. There is reassurance.'),
(2, 'WebsiteDescription', 'Escape to relax on a captivating beachfront retreat at Ramada Resort by Wyndham Dar Es Salaam. With a gorgeous private beach, globally-inspired restaurants, rooms with private balconies, and stylish event venues, our resort offers an ideal destination for business and leisure travelers visiting Tanzania.'),
(3, 'ContactMobile', '8571028462'),
(4, 'ContactEmail', 'StayHub@GMAIL.COM'),
(5, 'ContactAddress', '3176 Oakmound Drive, Chicago'),
(6, 'SocialMediaFacebook', 'https://www.facebook.com'),
(7, 'SocialMediaYoutube', 'https://www.youtube.com'),
(8, 'SocialMediaGoogle', 'https://www.google.com'),
(9, 'SocialMediaInstagram', 'https://www.instagram.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bedding_master`
--
ALTER TABLE `bedding_master`
  ADD PRIMARY KEY (`bedding_id`);

--
-- Indexes for table `bookings_billing_details`
--
ALTER TABLE `bookings_billing_details`
  ADD KEY `for_booking_id` (`booking_id`);

--
-- Indexes for table `bookings_details`
--
ALTER TABLE `bookings_details`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `for_user_id` (`user_id`);

--
-- Indexes for table `bookings_room_details`
--
ALTER TABLE `bookings_room_details`
  ADD KEY `for_room_booking_id` (`booking_id`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `room_master`
--
ALTER TABLE `room_master`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `user_master`
--
ALTER TABLE `user_master`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bedding_master`
--
ALTER TABLE `bedding_master`
  MODIFY `bedding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings_details`
--
ALTER TABLE `bookings_details`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `room_master`
--
ALTER TABLE `room_master`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_master`
--
ALTER TABLE `user_master`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings_billing_details`
--
ALTER TABLE `bookings_billing_details`
  ADD CONSTRAINT `for_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings_details` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings_details`
--
ALTER TABLE `bookings_details`
  ADD CONSTRAINT `for_user_id` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings_room_details`
--
ALTER TABLE `bookings_room_details`
  ADD CONSTRAINT `for_room_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings_details` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `contactus`
--
ALTER TABLE `contactus`
  ADD CONSTRAINT `contactus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
