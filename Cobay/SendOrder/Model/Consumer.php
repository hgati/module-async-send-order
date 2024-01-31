<?php
namespace Cobay\SendOrder\Model;

/** 비동기작업설명서, https://www.codilar.com/what-is-rabbitmq-used-for-in-magento-2/ */
// RabbitMQ 구성하고 사용하는 법을 배우자.
// https://webkul.com/blog/message-queue-in-magento2/
// https://webkul.com/blog/here-we-will-learn-how-to-configure-and-use-rabbitmq-in-magento-2-3/
// https://developer.adobe.com/commerce/php/development/components/message-queues/configuration/#queue_publisherxml
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\MessageQueue\ConsumerConfiguration;

class Consumer extends ConsumerConfiguration
{
    protected $logger;
    protected $jsonHelper;
    protected $scopeConfig;
	    
    public function __construct(
        Logger $logger,
        JsonHelper $jsonHelper,
        ScopeConfigInterface $scopeConfig 
    ) {
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->scopeConfig = $scopeConfig;
    }

    public function process($message)
    {   
        $data = $this->jsonHelper->jsonDecode($message, true);
        $this->logger->info("I'am Tom. BEST. You're a girl."); // magento setup:upgrade
        throw new Exception('에러발생');
    }
}