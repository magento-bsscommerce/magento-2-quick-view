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
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * BSS Commerce does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BSS Commerce does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   BSS
 * @package    Bss_Quickview
 * @author     Extension Team
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\Quickview\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $scopeConfig;
    /**
     * @var array
     */
    protected $quickviewOptions;
    protected $urlInterface;
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->urlInterface = $context->getUrlBuilder();
    }

    public function getBtnTextColor()
    {
        $color = $this->scopeConfig->getValue('bss_quickview/success_popup_design/button_text_color', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $color = ($color == '') ? '' : $color;
        return $color;
    }
    public function getBtnBackground()
    {
        $backGround = $this->scopeConfig->getValue('bss_quickview/success_popup_design/background_color', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $backGround = ($backGround == '') ? '' : $backGround;
        return $backGround;
    }
    public function getButtonText()
    {
        $buttonText = $this->scopeConfig->getValue('bss_quickview/success_popup_design/button_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $buttonText = ($buttonText == '') ? '' : $buttonText;
        return $buttonText;
    }
    public function enabled()
    {
        $isEnabled = $this->scopeConfig->getValue('bss_quickview/general/enable_product_listing', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $isEnabled = ($isEnabled == '') ? '' : $isEnabled;
        return $isEnabled;
    }
    public function getUrl()
    {
        $productUrl = $this->urlInterface->getUrl('bss_quickview/catalog_product/view/');
        return $productUrl;
    }
    public function getBaseUrl()
    {
        $baseUrl = $this->urlInterface->getUrl();
        return $baseUrl;
    }

    /**
     * Get Enable Remove Reivews
     * @return string
     */
    public function getRemoveReview() {
        $data = $this->scopeConfig->getValue(
            'bss_quickview/general/remove_reviews',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $data;
    }

    /**
     * Get Enable Remove More Information
     * @return string
     */
    public function getRemoveMoreInfo() {
        $data = $this->scopeConfig->getValue(
            'bss_quickview/general/remove_product_tab',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $data;
    }

    /**
     * @return string
     */
    public function getSkuTemplate()
    {
        $this->quickviewOptions = $this->scopeConfig->getValue('bss_quickview', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $removeSku = $this->quickviewOptions['general']['remove_sku'];
        if (!$removeSku) {
            return 'Magento_Catalog::product/view/attribute.phtml';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCustomCSS()
    {
        $this->quickviewOptions = $this->scopeConfig->getValue('bss_quickview', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return trim($this->quickviewOptions['general']['custom_css']);
    }

    /**
     * @return int
     */
    public function getCloseSeconds()
    {
        $this->quickviewOptions = $this->scopeConfig->getValue('bss_quickview', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return trim($this->quickviewOptions['general']['close_quickview']);
    }
}
