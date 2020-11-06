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
namespace Bss\Quickview\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class TrueFalse
 *
 * @package Bss\Quickview\Model\Config\Source
 */
class Add
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $redirectFactory;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Add constructor.
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        Context $context,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->context = $context;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Magento\Catalog\Controller\Product\Compare\Add $subject
     * @param $result
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function afterExcute(\Magento\Catalog\Controller\Product\Compare\Add $subject, $result)
    {
        $resultRedirect = $this->redirectFactory->create();
        $productId = (int)$this->context->getRequest()->getParam('product');
        $storeId = $this->storeManager->getStore()->getId();
        $product = $this->productRepository->getById($productId, false, $storeId);

        $params = $this->context->getRequest()->getParams();
        if (isset($params['bssquickview']) && $params['bssquickview'] == 1) {
            return $resultRedirect->setPath($product->getUrlModel()->getUrl($product));
        }
        return $result;
    }
}
