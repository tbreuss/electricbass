<?php

namespace app\components;

use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\Condition;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\GetItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\GetItemsResource;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\GetItemsResponse;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\Item;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\Configuration;
use GuzzleHttp\Client;

final class AmazonProductDetail
{
    /**
     * @var GetItemsResponse
     */
    private $response;

    /**
     * @var Item|null
     */
    private $item;

    /**
     * AmazonDetailProduct constructor.
     * @param GetItemsResponse $getItemsResponse
     */
    public function __construct(GetItemsResponse $getItemsResponse)
    {
        $this->response = $getItemsResponse;
        $this->item = $this->getItem();
    }

    /**
     * @return Item|null
     */
    private function getItem()
    {
        if ($this->response->getItemsResult() === null) {
            return null;
        }
        if ($this->response->getItemsResult()->getItems() === null) {
            return null;
        }
        $items = $this->response->getItemsResult()->getItems();
        return $items[0] ?? null;
    }

    /**
     * @param string $asin
     * @return static
     * @throws ApiException
     */
    public static function createByAsin(string $asin): self
    {
        $config = new Configuration();
        $config->setAccessKey(($_ENV['AMAZON_PAAPI5_ACCESS_KEY'] ?? ''));
        $config->setSecretKey(($_ENV['AMAZON_PAAPI5_SECRET_KEY'] ?? ''));
        $config->setHost(($_ENV['AMAZON_PAAPI5_HOST'] ?? ''));
        $config->setRegion(($_ENV['AMAZON_PAAPI5_REGION'] ?? ''));

        $apiInstance = new DefaultApi(new Client(), $config);

        /*$resources = [
            GetItemsResource::ITEM_INFOBY_LINE_INFO,
            GetItemsResource::ITEM_INFOCLASSIFICATIONS,
            GetItemsResource::ITEM_INFOCONTENT_INFO,
            GetItemsResource::ITEM_INFOCONTENT_RATING,
            GetItemsResource::ITEM_INFOEXTERNAL_IDS,
            GetItemsResource::ITEM_INFOFEATURES,
            GetItemsResource::ITEM_INFOMANUFACTURE_INFO,
            GetItemsResource::ITEM_INFOPRODUCT_INFO,
            GetItemsResource::ITEM_INFOTECHNICAL_INFO,
            GetItemsResource::ITEM_INFOTITLE,
            GetItemsResource::ITEM_INFOTRADE_IN_INFO,
            GetItemsResource::OFFERSLISTINGSPRICE,
            GetItemsResource::IMAGESPRIMARYLARGE,
            GetItemsResource::ITEM_INFOCONTENT_INFO,
            GetItemsResource::ITEM_INFOCONTENT_RATING
        ];*/
        $resources = GetItemsResource::getAllowableEnumValues();

        $getItemsRequest = new GetItemsRequest();
        $getItemsRequest->setItemIds([$asin]);
        $getItemsRequest->setPartnerTag(($_ENV['AMAZON_PAAPI5_PARTNER_TAG'] ?? ''));
        $getItemsRequest->setPartnerType(PartnerType::ASSOCIATES);
        $getItemsRequest->setResources($resources);
        $getItemsRequest->setCondition(Condition::_NEW);

        $getItemsResponse = $apiInstance->getItems($getItemsRequest);
        return new static($getItemsResponse);
    }

    public function hasItem(): bool
    {
        return $this->item !== null;
    }

    public function getAsin(): ?string
    {
        return $this->item->getASIN();
    }

    public function getDetailPageUrl(): ?string
    {
        return $this->item->getDetailPageURL();
    }

    public function getPrice(): ?array
    {
        if ($this->item->getOffers() !== null
            && $this->item->getOffers() !== null
            && $this->item->getOffers()->getListings() !== null
            && $this->item->getOffers()->getListings()[0]->getPrice() !== null
            && $this->item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount() !== null) {
            return [
                $this->item->getOffers()->getListings()[0]->getPrice()->getCurrency(),
                $this->item->getOffers()->getListings()[0]->getPrice()->getAmount()
            ];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getExternalIds(): array
    {
        $externalIds = [];

        if (!empty($values = $this->getEans())) {
            $externalIds['EAN'] = $values;
        }

        if (!empty($values = $this->getISBNs())) {
            $externalIds['ISBN'] = $values;
        }

        if (!empty($values = $this->getUPCs())) {
            $externalIds['UPC'] = $values;
        }

        return $externalIds;
    }

    /**
     * @return array
     */
    public function getEANs(): array
    {
        if ($this->item->getItemInfo() === null
            || $this->item->getItemInfo()->getExternalIds() === null
            || $this->item->getItemInfo()->getExternalIds()->getEANs() === null) {
            return [];
        }
        return $this->item->getItemInfo()->getExternalIds()->getEANs()->getDisplayValues();
    }

    /**
     * @return array
     */
    public function getISBNs(): array
    {
        if ($this->item->getItemInfo() === null
            || $this->item->getItemInfo()->getExternalIds() === null
            || $this->item->getItemInfo()->getExternalIds()->getISBNs() === null) {
            return [];
        }
        return $this->item->getItemInfo()->getExternalIds()->getISBNs()->getDisplayValues();
    }

    /**
     * @return array
     */
    public function getUPCs(): array
    {
        if ($this->item->getItemInfo() === null
            || $this->item->getItemInfo()->getExternalIds() === null
            || $this->item->getItemInfo()->getExternalIds()->getUPCs() === null) {
            return [];
        }
        return $this->item->getItemInfo()->getExternalIds()->getUPCs()->getDisplayValues();
    }

    public function getTitle(): ?string
    {
        $itemInfo = $this->item->getItemInfo();
        if (is_null($itemInfo)) {
            return null;
        }
        $title = $itemInfo->getTitle();
        if ($title === null) {
            return null;
        }
        $displayValue = $title->getDisplayValue();
        if ($displayValue === null) {
            return null;
        }
        return $displayValue;
    }

    public function getAuthor(): string
    {
        $authors = $this->findContributorsByRole(['Autor']);
        return implode(', ', $authors);
    }

    private function findContributorsByRole(array $roles): array
    {
        $contributors = $this->getContributors();
        $filtered = array_filter($contributors, function ($v) use ($roles) {
            return in_array($v['role'], $roles);
        });
        return array_column($filtered, 'name');
    }

    public function getContributors(): array
    {
        $itemInfo = $this->item->getItemInfo();
        if ($itemInfo === null) {
            return [];
        }
        $byLineInfo = $itemInfo->getByLineInfo();
        if ($byLineInfo === null) {
            return [];
        }
        $contributors = $byLineInfo->getContributors();
        if ($contributors === null) {
            return [];
        }
        $return = [];
        foreach ($contributors as $contributor) {
            $return[] = [
                'role' => $contributor->getRole(),
                'name' => $contributor->getName()
            ];
        }
        return $return;
    }

    public function getArtist(): string
    {
        $authors = $this->findContributorsByRole(['Künstler']);
        return implode(', ', $authors);
    }

    public function getBrand(): string
    {
        $itemInfo = $this->item->getItemInfo();
        if ($itemInfo === null) {
            return '';
        }
        $byLineInfo = $itemInfo->getByLineInfo();
        if ($byLineInfo === null) {
            return '';
        }
        $brand = $byLineInfo->getBrand();
        if ($brand === null) {
            return '';
        }
        return $brand->getDisplayValue();
    }

    public function getManufacturer(): string
    {
        $itemInfo = $this->item->getItemInfo();
        if ($itemInfo === null) {
            return '';
        }
        $byLineInfo = $itemInfo->getByLineInfo();
        if ($byLineInfo === null) {
            return '';
        }
        $manufacturer = $byLineInfo->getManufacturer();
        if ($manufacturer === null) {
            return '';
        }
        return $manufacturer->getDisplayValue();
    }

    public function getProductGroup(): ?string
    {
        if (($this->item->getItemInfo() === null) ||
            ($this->item->getItemInfo()->getClassifications() === null) ||
            ($this->item->getItemInfo()->getClassifications()->getProductGroup() === null)) {
            return null;
        }
        return $this->item->getItemInfo()->getClassifications()->getProductGroup()->getDisplayValue();
    }

    public function getBinding(): ?string
    {
        if (($this->item->getItemInfo() === null) ||
            ($this->item->getItemInfo()->getClassifications() === null) ||
            ($this->item->getItemInfo()->getClassifications()->getBinding() === null)) {
            return null;
        }
        return $this->item->getItemInfo()->getClassifications()->getBinding()->getDisplayValue();
    }

    public function getFeatures(): ?array
    {
        if (($this->item->getItemInfo() === null) ||
            ($this->item->getItemInfo()->getFeatures() === null) ||
            ($this->item->getItemInfo()->getFeatures()->getDisplayValues() === null)) {
            return null;
        }
        return $this->item->getItemInfo()->getFeatures()->getDisplayValues();
    }

    public function getItemPartNumber(): ?string
    {
        if (($this->item->getItemInfo() === null) ||
            ($this->item->getItemInfo()->getManufactureInfo() === null) ||
            ($this->item->getItemInfo()->getManufactureInfo()->getItemPartNumber() === null)) {
            return null;
        }
        return $this->item->getItemInfo()->getManufactureInfo()->getItemPartNumber()->getDisplayValue();
    }

    public function getModelNumber(): ?string
    {
        if (($this->item->getItemInfo() === null) ||
            ($this->item->getItemInfo()->getManufactureInfo() === null) ||
            ($this->item->getItemInfo()->getManufactureInfo()->getModel() === null)) {
            return null;
        }
        return $this->item->getItemInfo()->getManufactureInfo()->getModel()->getDisplayValue();
    }

    public function getImageUrl(): ?string
    {
        $images = $this->item->getImages();
        if ($images === null) {
            return null;
        }
        $primary = $images->getPrimary();
        if ($primary === null) {
            return null;
        }
        $large = $primary->getLarge();
        if ($large === null) {
            return null;
        }
        $url = $large->getUrl();
        if ($url === null) {
            return null;
        }
        return $url;
    }
}
