<?php
namespace Aschaeffer\SonataEditableListBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Listable
{
    const ANNOTATION_NAME = 'Aschaeffer\\SonataEditableListBundle\\Annotation\\Listable';

    /**
     * @Required
     *
     * @var string
     */
    public $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}