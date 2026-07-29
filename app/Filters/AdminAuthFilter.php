<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('admin_id')) {
            return;
        }

        $message = 'Silakan login terlebih dahulu.';

        // ponytail: fetch/axios di semua module admin (games, products, dst)
        // ditandai lewat header X-Requested-With (di-set global oleh admin.js).
        // Tanpa cek ini, session expired bikin AJAX nerima HTML redirect,
        // res.json() di JS meledak/silent-fail.
        if ($request->isAJAX() || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            return service('response')->setJSON([
                'success'   => false,
                'message'   => $message,
                'data'      => null,
                'csrf_hash' => function_exists('csrf_hash') ? csrf_hash() : null,
            ])->setStatusCode(401);
        }

        return redirect()->to(admin_url('login'))->with('alert', [
            'type'    => 'error',
            'message' => $message,
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
