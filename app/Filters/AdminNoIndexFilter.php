<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminNoIndexFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nothing needed before the response exists.
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }
}
