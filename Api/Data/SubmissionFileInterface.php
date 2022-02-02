<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

interface SubmissionFileInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FILE_ID             = 'file_id';
    const SUBMISSION_ID       = 'submission_id';
    const FORM_ID             = 'form_id';
    const NAME                = 'name';
    const PATH                = 'path';
    const URL                 = 'url';
    const MIME_TYPE           = 'mime_type';
    const SIZE                = 'size';
    const CREATION_TIME       = 'creation_time';
    /**#@-*/

    /**
     * Get the file id
     * @return int
     */
    public function getFileId();

    /**
     * Get the form submission id
     * @return int
     */
    public function getSubmissionId();

    /**
     * Get the form id
     * @return int
     */
    public function getFormId();

    /**
     * Get the file name
     * @return string
     */
    public function getName();

    /**
     * Get the file path
     * @return string
     */
    public function getPath();

    /**
     * Get the file url
     * @return string
     */
    public function getUrl();

    /**
     * Get the file mime type
     * @return string
     */
    public function getMimeType();

    /**
     * Get the file size
     * @return string
     */
    public function getSize();

    /**
     * Get form creation time
     * @return string
     */
    public function getCreationTime();

    /**
     * Set the file id
     * @param $fileId
     * @return $this
     */
    public function setFileId($fileId);

    /**
     * Set the file submission id
     * @param $submissionId
     * @return $this
     */
    public function setSubmissionId($submissionId);

    /**
     * Set the form id
     * @param $formId
     * @return $this
     */
    public function setFormId($formId);

    /**
     * Set the file name
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the file path
     * @param $path
     * @return $this
     */
    public function setPath($path);

    /**
     * Set the file url
     * @param $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Set the file mime type
     * @param $mimeType
     * @return $this
     */
    public function setMimeType($mimeType);

    /**
     * Set the file size
     * @param $size
     * @return $this
     */
    public function setSize($size);

    /**
     * Set file creation time
     * @param $creationTime
     * @return SubmissionInterface
     */
    public function setCreationTime($creationTime);
}
