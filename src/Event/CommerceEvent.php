<?php

namespace App\Event;

use App\Entity\Commerce;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class CommerceEvent extends Event
{
    public const PRE_CREATE  = 'app.commerce.pre_create';

    public const POST_CREATE = 'app.commerce.post_create';

    public const PRE_EDIT    = 'app.commerce.pre_edit';

    public const POST_EDIT   = 'app.commerce.post_edit';

    public const PRE_DELETE  = 'app.commerce.pre_delete';

    public const POST_DELETE = 'app.commerce.post_delete';

    public const SHOW        = 'app.commerce.show';

    /**
     * @var Commerce
     */
    protected $commerce;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SessionInterface
     */
    protected $session;

    public function  __construct(Commerce $commerce, Request $request = null)
    {
        $this->request = $request;
        $this->commerce = $commerce;
    }

    /**
     * @return Commerce
     */
    public function getCommerce(): Commerce
    {
        return $this->commerce;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}


