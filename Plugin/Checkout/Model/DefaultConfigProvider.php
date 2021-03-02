<?php
namespace Xigen\CheckoutPageCustomAttributes\Plugin\Checkout\Model;

use Magento\Checkout\Model\Session as CheckoutSession;

class DefaultConfigProvider
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * Constructor
     *
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        array $result
    ) {
        $items = $result['totalsData']['items'];
        foreach ($items as $index => $item) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($item['item_id']);
            if ($quoteItem && $quoteItem->getProduct()) {
                $result['quoteItemData'][$index]['shipping'] = (string) __(
                    'Est. delivery: %1 Working Days',
                    $quoteItem->getProduct()->getData('delivery_time')
                );
            }
        }
        return $result;
    }
}
