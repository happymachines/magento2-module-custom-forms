<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Submission;

use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\SubmissionRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class Submission
 * @package HappyMachines\CustomForms\ViewModel\Submission
 */
class Submission implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var SubmissionRepositoryInterface
     */
    private $submissionRepository;

    /**
     * @var SubmissionInterface
     */
    private $submission;

    /**
     * Submission constructor.
     * @param RequestInterface $request
     * @param JsonSerializer $serializer
     * @param SubmissionRepositoryInterface $submissionRepository
     */
    public function __construct(
        RequestInterface $request,
        JsonSerializer $serializer,
        SubmissionRepositoryInterface  $submissionRepository
    ) {
        $this->request = $request;
        $this->serializer = $serializer;
        $this->submissionRepository = $submissionRepository;
    }

    /**
     * @param int|null $id
     * @return SubmissionInterface
     */
    public function getSubmission($id = null)
    {
        if (!$id) {
            $id = $this->request->getParam('submission_id');
        }

        if (!$this->submission) {
            $this->submission = $this->submissionRepository->getById($id);
        }

        return $this->submission;
    }

    /**
     * @return array
     */
    public function getSubmissionData()
    {
        $submission = $this->getSubmission();

        return $this->serializer->unserialize($submission->getSubmissionData());
    }
}
