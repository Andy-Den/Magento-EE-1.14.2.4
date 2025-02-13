<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Rolepermissions
 */

$this->startSetup();

$this->run("
ALTER TABLE `{$this->getTable('amrolepermissions/rule')}` CHANGE `products` `products` TEXT;
ALTER TABLE `{$this->getTable('amrolepermissions/rule')}` CHANGE `categories` `categories` TEXT;
");

$this->endSetup();
