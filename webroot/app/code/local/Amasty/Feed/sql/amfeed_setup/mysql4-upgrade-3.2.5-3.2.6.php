<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Feed
 */
$installer = $this;
$installer->startSetup();

$installer->run("
insert into `{$this->getTable('amfeed/template')}` (`status`, `type`, `title`, `filename`, `mode`, `cond_stock`, `cond_disabled`, `cond_type`, `cond_attribute_sets`, `cond_advanced`, `xml_header`, `xml_body`, `xml_footer`, `xml_item`, `csv`, `csv_header`, `csv_header_static`, `csv_enclosure`, `csv_delimiter`, `frm_date`, `frm_price`, `frm_price_dec_point`, `frm_price_thousands_sep`, `frm_url`, `frm_image_url`, `frm_dont_use_category_in_url`, `frm_use_parent`, `default_image`, `delivery_type`, `delivered`, `send_email`, `ftp_host`, `ftp_user`, `ftp_pass`, `ftp_folder`, `ftp_is_passive`, `info_total`, `info_cnt`, `info_errors`, `freq`, `on_days`, `hour_from`, `hour_to`, `error_email`, `max_images`, `condition_serialized`) values('0','1','ShopAlike','shopalike','0','0','0','simple',NULL,'a:0:{}','<?xml version=\"1.0\"?> <rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\"> <channel>','<itemId>{type=\"attribute\" value=\"sku\" format=\"as_is\" length=\"255\" optional=\"no\" parent=\"no\"}</itemId>\r\n<name>{type=\"attribute\" value=\"name\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</name>\r\n<TopCategory>{type=\"attribute\" value=\"category\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</TopCategory>\r\n<category1>{type=\"attribute\" value=\"category_name\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</category1>\r\n<gender>{type=\"attribute\" value=\"gender\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</gender>\r\n<color>{type=\"attribute\" value=\"color\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</color>\r\n<brand>{type=\"attribute\" value=\"manufacturer\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</brand>\r\n<material>{type=\"attribute\" value=\"material\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</material>\r\n<description>{type=\"attribute\" value=\"description\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</description>\r\n<price>{type=\"attribute\" value=\"price\" format=\"price\" length=\"\" optional=\"no\" parent=\"no\"}</price>\r\n<sale>{type=\"attribute\" value=\"special_price\" format=\"price\" length=\"\" optional=\"no\" parent=\"no\"}</sale>\r\n<currency>{type=\"text\" value=\"EUR\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</currency>\r\n<deliveryTime>{type=\"text\" value=\"1-2 days\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</deliveryTime>\r\n<shippingCosts>{type=\"text\" value=\"From $0\" format=\"as_is\" length=\"\" optional=\"no\" parent=\"no\"}</shippingCosts>\r\n<sizes>{type=\"attribute\" value=\"size\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"yes\"}</sizes>\r\n<image>{type=\"images\" value=\"image_1\" format=\"base\" length=\"\" optional=\"no\" parent=\"no\"}</image>\r\n<image2>{type=\"images\" value=\"image_2\" format=\"base\" length=\"\" optional=\"no\" parent=\"no\"}</image2>\r\n<image3>{type=\"images\" value=\"image_3\" format=\"base\" length=\"\" optional=\"no\" parent=\"no\"}</image3>\r\n<deepLink>{type=\"attribute\" value=\"url\" format=\"html_escape\" length=\"\" optional=\"no\" parent=\"no\"}</deepLink>','</channel> </rss>','item','a:0:{}','0','','34','44','','0','.',',','0','0','0','0','0','0','0',NULL,NULL,NULL,NULL,NULL,'0','101','101','0','0','','0','0',NULL,'0','a:0:{}');");

$installer->run("
    UPDATE`{$this->getTable('amfeed/template')}`
    SET store_id = " . Mage::app()->getStore()->getId() . "
    ;
");