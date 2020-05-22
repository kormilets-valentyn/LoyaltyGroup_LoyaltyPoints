<?php

namespace LoyaltyGroup\LoyaltyPoints\Model\Total;

use LoyaltyGroup\LoyaltyPoints\Block\Post;
use Magento\Customer\Model\Session;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class LoyaltyPoints extends AbstractTotal
{
    private $getPoints;
    private $points;
    private $session;

    /**
     * LoyaltyPoints constructor.
     * @param Post $getPoints
     * @param Session $session
     */
    public function __construct(Post $getPoints, Session $session)
    {
        $this->setCode('loyalty_points');
        $this->getPoints = $getPoints;
        $this->session = $session;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this|bool|AbstractTotal
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }
        $a = $this->session->getUsePoints();
        if ($a == 1) {
            $this->points = $this->getPoints->getPointsCount();
            $amount = $this->points;
            $allTotalValue = array_sum($total->getAllTotalAmounts());
            $allBaseTotalValue = array_sum($total->getAllBaseTotalAmounts());
            $totalSale = $amount > $allTotalValue ? ($allTotalValue - 0.01) : $amount;
            $totalBaseSale = $amount > $allBaseTotalValue ? ($allBaseTotalValue - 0.01) : $amount;
            $total->addTotalAmount('loyalty_points', -$totalSale);
            $total->addBaseTotalAmount('loyalty_points', -$totalBaseSale);
            $this->session->setCancelOfPoints($totalSale);
            return $this;
        } else {
            $this->session->setCancelOfPoints(0);
            return false;
        }
    }

    /**
     * @param Total $total
     */
    protected function clearValues(Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(
        Quote $quote,
        Total $total
    ) {
        return [
                'code' => $this->getCode(),
                'title' => 'Loyalty Points',
                'value' => -$this->getPoints->getPointsCount()
                ];
    }
}
