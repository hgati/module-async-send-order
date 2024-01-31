<?php
declare(strict_types=1);

namespace Cobay\SendOrder\Observer;

use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Psr\Log\LoggerInterface;

class CheckoutSuccess implements ObserverInterface
{
	protected $publisher;
	protected $logger;
	protected $jsonHelper;

	public function __construct(
		PublisherInterface $publisher,
		LoggerInterface $logger,
		JsonHelper $jsonHelper
	) {
		$this->publisher = $publisher;
		$this->logger = $logger;
		$this->jsonHelper = $jsonHelper;
    }

    public function execute(Observer $observer) 
	{
		try {

			$order = $observer->getEvent()->getOrder();
			$order_id = $order->getId();
			$order_incr_id = $order->getIncrementId();
			$data = $this->jsonHelper->jsonEncode([
				'order_id' => $order_id,
				'order_incr_id' => $order_incr_id
			]);

			// 큐에 데이터를 추가하여 비동기 작업으로 처리
			$this->publisher->publish($topic='cobay.send.order.external.system.topic', $data);
			$this->logger->info("$order_incr_id : 주문데이터전송 비동기처리에 추가하였습니다");

		}catch(\Exception $e){
			$this->logger->critical($e->getMessage());
		}
	}
}