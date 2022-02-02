<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

/**
 * Interface SubmissionInterface
 * @package HappyMachines\CustomForms\Api\Data
 */
interface SubmissionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SUBMISSION_ID       = 'submission_id';
    const FORM_ID             = 'form_id';
    const CUSTOMER_ID         = 'customer_id';
    const CUSTOMER_EMAIL      = 'customer_email';
    const QUOTE_ID            = 'quote_id';
    const SUBMISSION_DATA     = 'submission_data';
    const CREATION_TIME       = 'creation_time';
    /**#@-*/

    /**
     * Get the form submission id
     * @return int
     */
    public function getSubmissionId();

    /**
     * Get the related form id
     * @return int
     */
    public function getFormId();

    /**
     * Get the customer id
     * @return int
     */
    public function getCustomerId();

    /**
     * Get customer email
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Get the customer quote id
     * @return int
     */
    public function getQuoteId();

    /**
     * Get form submission data
     * @return string
     */
    public function getSubmissionData();

    /**
     * Get form creation time
     * @return string
     */
    public function getCreationTime();

    /**
     * Set the form submission id
     * @param $submissionId
     * @return SubmissionInterface
     */
    public function setSubmissionId($submissionId);

    /**
     * Set the related form id
     * @param $formId
     * @return SubmissionInterface
     */
    public function setFormId($formId);

    /**
     * Set the customer id
     * @param $customerId
     * @return SubmissionInterface
     */
    public function setCustomerId($customerId);

    /**
     * Set customer email
     * @param $email
     * @return SubmissionInterface
     */
    public function setCustomerEmail($email);

    /**
     * Set the customer quote id
     * @param $id
     * @return int
     */
    public function setQuoteId($id);

    /**
     * Set form submission data
     * @param $submissionData
     * @return SubmissionInterface
     */
    public function setSubmissionData($submissionData);

    /**
     * Set form creation time
     * @param $creationTime
     * @return SubmissionInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Get unserialized form submission data
     * @return array
     */
    public function getUnserializedSubmissionData();
}
