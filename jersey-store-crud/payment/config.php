<?php
// eSewa ePay v2 -- TEST / UAT environment. Replace with production values before going live.
const ESEWA_FORM_URL    = 'https://rc-epay.esewa.com.np/api/epay/main/v2/form';
const ESEWA_STATUS_URL  = 'https://rc.esewa.com.np/api/epay/transaction/status/';
const ESEWA_PRODUCT_CODE = 'EPAYTEST';
const ESEWA_SECRET_KEY   = '8gBm/:&EnhH.1/q';   // public eSewa test secret key. NEVER commit a real one.

// Absolute URLs eSewa redirects back to. Adjust host/port if not on localhost:80.
const ESEWA_SUCCESS_URL = 'http://localhost' . BASE_URL . '/payment/esewa_success.php';
const ESEWA_FAILURE_URL = 'http://localhost' . BASE_URL . '/payment/esewa_failure.php';

function esewa_signature(string $message): string {
    return base64_encode(hash_hmac('sha256', $message, ESEWA_SECRET_KEY, true));
}
