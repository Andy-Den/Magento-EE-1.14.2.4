/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Uploader
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */
package varien.upload
{
    import flash.events.Event;

    public class UploaderEvent extends Event
    {
        /**
         * @eventType progress
         */
        public static const PROGRESS:String 	= 'progress';

        /**
         * @eventType error
         */
        public static const ERROR:String 		= 'error';

        /**
         * @eventType select
         */
        public static const SELECT:String 		= 'select';

        /**
         * @eventType complete
         */
        public static const COMPLETE:String 	= 'complete';

        /**
         * @eventType cancel
         */
        public static const CANCEL:String 		= 'cancel';

        /**
         * @eventType remove
         */
        public static const REMOVE:String 		= 'remove';

        /**
         * @eventType removeall
         */
        public static const REMOVE_ALL:String 		= 'removeall';

        protected var _data:Object;

        public function UploaderEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
        {
            super(type, bubbles, cancelable);

        }

        public function get data():Object
        {
            return _data;
        }

        public function set data(value:Object):void
        {
            _data = value;
        }
    }
}
