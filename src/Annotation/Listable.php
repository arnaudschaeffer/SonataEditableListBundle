<?php
namespace Aschaeffer\SonataEditableListBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Listable
{
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