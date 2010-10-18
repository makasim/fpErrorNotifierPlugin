<?php

$pluginLibDir = realpath(dirname(__FILE__) . '/../lib');

//core
require_once $pluginLibDir . '/fpErrorNotifier.php';

//drivers
require_once $pluginLibDir . '/driver/fpBaseErrorNotifierDriver.php';
require_once $pluginLibDir . '/driver/fpErrorNotifierDriverFile.php';
require_once $pluginLibDir . '/driver/fpErrorNotifierDriverNull.php';
require_once $pluginLibDir . '/driver/mail/fpBaseErrorNotifierDriverMail.php';
require_once $pluginLibDir . '/driver/mail/fpErrorNotifierDriverMailNative.php';
require_once $pluginLibDir . '/driver/mail/fpErrorNotifierDriverMailSymfony.php';

//handlers
require_once $pluginLibDir . '/handler/fpErrorNotifierHandler.php';
require_once $pluginLibDir . '/handler/fpErrorNotifierHandlerIgnore.php';

//messages
require_once $pluginLibDir . '/message/fpBaseErrorNotifierMessage.php';
require_once $pluginLibDir . '/message/fpErrorNotifierMessage.php';
require_once $pluginLibDir . '/message/fpErrorNotifierMessageHelper.php';

//decorators
require_once $pluginLibDir . '/decorator/fpBaseErrorNotifierDecorator.php';
require_once $pluginLibDir . '/decorator/fpErrorNotifierDecoratorHtml.php';
require_once $pluginLibDir . '/decorator/fpErrorNotifierDecoratorText.php';

//util
require_once $pluginLibDir . '/util/fpErrorNotifierNullObject.php';
require_once $pluginLibDir . '/util/fpErrorNotifierErrorCode.php';