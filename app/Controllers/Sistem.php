<?php

namespace App\Controllers;
use DateTime;

class Sistem extends BaseController {
    
    public function statusopcacheReset() {
        if (!function_exists('opcache_reset')) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => 'OPCache extension not enabled',
            ]);
        }
        
        $ok = @opcache_reset();
        
        return $this->response->setJSON([
            'status' => (bool) $ok,
            'message' => $ok ? 'OK' : 'FAILED',
        ]);
    }
    
}