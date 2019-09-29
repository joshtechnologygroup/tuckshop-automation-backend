-- MySQL dump 10.13  Distrib 5.6.40, for Linux (x86_64)
--
-- Host: localhost    Database: tuckshop
-- ------------------------------------------------------
-- Server version 5.6.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `jtg_users`
--

DROP TABLE IF EXISTS `jtg_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jtg_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `is_active` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Is Active',
  `prefix` varchar(40) DEFAULT NULL COMMENT 'Name Prefix',
  `firstname` varchar(255) DEFAULT NULL COMMENT 'First Name',
  `middlename` varchar(255) DEFAULT NULL COMMENT 'Middle Name/Initial',
  `lastname` varchar(255) DEFAULT NULL COMMENT 'Last Name',
  `suffix` varchar(40) DEFAULT NULL COMMENT 'Name Suffix',
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `food_preference` varchar(40) DEFAULT 'Veg' COMMENT 'Food Preference',
  `profile_picture` text COMMENT 'Profile Picture',
  `password_hash` varchar(128) DEFAULT NULL COMMENT 'Password_hash',
  `gender` varchar(128) DEFAULT NULL COMMENT 'Gender',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
  `disabled_at` timestamp NULL DEFAULT NULL COMMENT 'User Disabled At',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `JTG_USERS_EMAIL` (`email`),
  KEY `JTG_USERS_FIRSTNAME` (`firstname`),
  KEY `JTG_USERS_LASTNAME` (`lastname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='JTG Users Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tuckshop_products`
--

DROP TABLE IF EXISTS `tuckshop_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tuckshop_products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Product ID',
  `sku` varchar(64) DEFAULT NULL COMMENT 'SKU',
  `product_image` text COMMENT 'Product Image',
  `product_desc` text COMMENT 'Product Description',
  `product_inventory` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Product Inventory Count',
  `product_name` varchar(255) DEFAULT NULL COMMENT 'Product Name',
  `is_active` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Is Active',
  `product_barcode` varchar(255) DEFAULT NULL COMMENT 'Product Barcode',
  `price` decimal(20,4) DEFAULT NULL COMMENT 'Product Price',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Time',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
  `disabled_at` timestamp NULL DEFAULT NULL COMMENT 'User Disabled At',
  PRIMARY KEY (`product_id`),
  KEY `TUCKSHOP_PRODUCTS_SKU` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tuckshop Products Table';
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `tuckshop_orders`
--
DROP TABLE IF EXISTS `tuckshop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tuckshop_orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Order Id',
  `status` varchar(32) DEFAULT NULL COMMENT 'Status',
  `coupon_code` varchar(255) DEFAULT NULL COMMENT 'Coupon Code',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'Customer Id',
  `discount_amount` decimal(20,4) DEFAULT NULL COMMENT 'Discount Amount',
  `grand_total` decimal(20,4) DEFAULT NULL COMMENT 'Grand Total',
  `customer_email` varchar(128) DEFAULT NULL COMMENT 'Customer Email',
  `customer_note` text COMMENT 'Customer Note',
  `total_item_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Total Item Count',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
  PRIMARY KEY (`order_id`),
  KEY `SALES_ORDER_STATUS` (`status`),
  KEY `SALES_ORDER_CREATED_AT` (`created_at`),
  KEY `SALES_ORDER_USER_ID` (`user_id`),
  KEY `SALES_ORDER_UPDATED_AT` (`updated_at`),
  CONSTRAINT `TUCKSHOP_ORDERS_USER_ID_JTG_USERS_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `jtg_users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tuckshop Orders Table';

--
-- Table structure for table `sales_order_item`
--

DROP TABLE IF EXISTS `tuckshop_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tuckshop_order_item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Item Id',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order Id',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT 'Product Id',
  `sku` varchar(255) DEFAULT NULL COMMENT 'Sku',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `qty_ordered` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Quantity Ordered',
  `item_price` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Base Price',
  `row_total` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT 'Row Total',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
  PRIMARY KEY (`item_id`),
  KEY `TUCKSHOP_ORDER_ITEM_ORDER_ID` (`order_id`),
  KEY `TUCKSHOP_ORDER_ITEM_PRODUCT_ID` (`product_id`),
  CONSTRAINT `TUCKSHOP_ORDER_ITEM_ORDER_ID_TUCKSHOP_ORDERS_ORDER_ID` FOREIGN KEY (`order_id`) REFERENCES `tuckshop_orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sales Flat Order Item';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tuckshop_admin_user`
--

DROP TABLE IF EXISTS `tuckshop_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tuckshop_admin_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `firstname` varchar(32) DEFAULT NULL COMMENT 'User First Name',
  `lastname` varchar(32) DEFAULT NULL COMMENT 'User Last Name',
  `email` varchar(128) DEFAULT NULL COMMENT 'User Email',
  `username` varchar(40) DEFAULT NULL COMMENT 'User Login',
  `password` varchar(255) NOT NULL COMMENT 'User Password',
  `is_active` smallint(6) NOT NULL DEFAULT '1' COMMENT 'User Is Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'User Created Time',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'User Modified Time',
  `disabled_at` timestamp NULL DEFAULT NULL COMMENT 'User Disabled At',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `TUCKSHOP_ADMIN_USER_USERNAME` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Admin User Table';
/*!40101 SET character_set_client = @saved_cs_client */;