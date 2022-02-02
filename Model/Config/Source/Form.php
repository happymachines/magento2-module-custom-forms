<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Config\Source;

use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Form
 * @package HappyMachines\CustomForms\Model\Config\Source
 */
class Form implements OptionSourceInterface
{
    /**
     * @var SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * Form constructor.
     * @param SearchCriteriaInterface $searchCriteria
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        SearchCriteriaInterface $searchCriteria,
        FormRepositoryInterface $formRepository
    )
    {
        $this->searchCriteria = $searchCriteria;
        $this->formRepository = $formRepository;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return $this->buildFormOptions();
    }

    /**
     * @return \HappyMachines\CustomForms\Api\Data\FormSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getFormCollection()
    {
        return $this->formRepository->getList($this->searchCriteria);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function buildFormOptions()
    {
        $formOptionsData = [];
        $formCollection = $this->getFormCollection();
        foreach ($formCollection->getItems() as $form) {
            $formOptionsData[] = [
                'value' => $form->getFormId(),
                'label' => $form->getName()
            ];
        }
        return $formOptionsData;
    }
}
