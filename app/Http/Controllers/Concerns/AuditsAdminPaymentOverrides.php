<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait AuditsAdminPaymentOverrides
{
    protected function authorizeAdminPaymentOverride(Request $request, $action)
    {
        $request->validate([
            'admin_payment_confirmation' => 'required|in:CONFIRM',
        ]);

        Log::info('admin.payment_override', [
            'action' => $action,
            'admin_id' => auth()->id(),
            'admin_email' => auth()->user()->email,
            'store_id' => $request->input('store_id', $request->input('store')),
            'owner_id' => $request->input('ownerId'),
            'ip' => $request->ip(),
            'payload_keys' => array_keys($request->except(['_token', 'password'])),
        ]);
    }
}
