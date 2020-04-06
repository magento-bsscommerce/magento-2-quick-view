<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_Quickview
 * @author     Extension Team
 * @copyright  Copyright (c) 2019-2020 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\Quickview\Controller\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\ViewModel\Product\Checker\AddToCompareAvailability;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Add
 *
 * @package Bss\Quickview\Controller\Catalog\Product
 */
class Add extends \Magento\Catalog\Controller\Product\Compare\Add
{
    /**
     * @var ObjectManagerInterface
     */
    protected $myObjectManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    protected $compare;

    /**
     * Add constructor.
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Catalog\Helper\Product\Compare $compare
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Visitor $customerVisitor
     * @param \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param PageFactory $resultPageFactory
     * @param ProductRepositoryInterface $productRepository
     * @param AddToCompareAvailability|null $compareAvailability
     */
    public function __construct(
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Helper\Product\Compare $compare,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        AddToCompareAvailability $compareAvailability = null
    ) {
        $this->compare = $compare;
        $this->escaper = $escaper;
        parent::__construct(
            $context,
            $compareItemFactory,
            $itemCollectionFactory,
            $customerSession,
            $customerVisitor,
            $catalogProductCompareList,
            $catalogSession,
            $storeManager,
            $formKeyValidator,
            $resultPageFactory,
            $productRepository,
            $compareAvailability
        );
    }

    /**
     * Add product
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $this->myObjectManager = $this->_objectManager;
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setRefererUrl();
        }

        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId && ($this->_customerVisitor->getId() || $this->_customerSession->isLoggedIn())) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (\Exception $e) {
                $product = null;
            }

            if ($product) {
                $this->_catalogProductCompareList->addProduct($product);
                $productName = $this->escaper->escapeHtml($product->getName());
                $this->messageManager->addSuccessMessage(__('You added product %1 to the comparison list.', $productName));
                $this->_eventManager->dispatch('catalog_product_compare_add_product', ['product' => $product]);
            }

            $this->compare->calculate();
        }
        $params = $this->getRequest()->getParams();
        if (isset($params['bssquickview']) && $params['bssquickview'] == 1) {
            return $resultRedirect->setPath($product->getUrlModel()->getUrl($product));
        }
        return $resultRedirect->setRefererOrBaseUrl();
    }
}
