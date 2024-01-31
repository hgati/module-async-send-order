<?php

namespace Cobay\SendOrder\Plugin;

use Psr\Log\LoggerInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;

class OrderManagementPlugin
{
	protected $publisher;
    protected $logger;

    public function __construct(
		PublisherInterface $publisher,
        LoggerInterface $logger
	) {
        $this->logger = $logger;
		$this->publisher = $publisher;
    }

    public function afterPlace(OrderManagementInterface $subject, OrderInterface $order)
    {
        try {
			// 큐에 데이터를 추가하여 비동기 작업으로 처리
			$this->publisher->publish($topic='send.order.external.system.queue', json_encode(['order_id' => $order->getId()]));
            $this->logger->info('Reservation SendOrder for Qxpress System. Order ID: ' . $order->getIncrementId());
        } catch (\Exception $e) {
            $this->logger->error('Error Reservation SendOrder for Qxpress System. Error: ' . $e->getMessage());
        }

        return $order;
    }
}
