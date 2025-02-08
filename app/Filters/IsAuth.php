<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class IsAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!isset(session()->user))
	    {
	        return redirect()->to(base_url('/login'));
	    }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
