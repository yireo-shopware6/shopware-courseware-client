<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use Exception;
use InvalidArgumentException;

class Status
{
    /**
     * @var string[]
     */
    private $allowedStatuses = [
        'draft' => ['Draft','#eee'],
        'skip' => ['Skip','#ddd'],
        'pending' => ['Pending','#ccc'],
        'writing' => ['Writing','#ffcd93'],
        'recording' => ['Recording','#f9ee87'],
        'uploaded' => ['Uploaded','#93ffc1'],
        'complete' => ['Complete', '#99d699'],
    ];

    /**
     * @var string
     */
    private $code;

    /**
     * Status constructor.
     * @param string $code
     */
    public function __construct(string $code = 'draft')
    {
        if (empty($code)) {
            $code = 'draft';
        }

        if (!array_key_exists($code, $this->allowedStatuses)) {
            throw new InvalidArgumentException('Invalid status code "' . $code . '"');
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->allowedStatuses[$this->code][0];
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->allowedStatuses[$this->code][1];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function allowPublishing(): bool
    {
        if (in_array($this->getCode(), ['complete', 'uploading'])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }
}