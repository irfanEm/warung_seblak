<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WhitelistMidtransIP
{
    /**
     * Handle an incoming request.
     *
     * Validates that the request originates from a whitelisted Midtrans IP range.
     * If MIDTRANS_WHITELIST_IPS is empty, the check is skipped (allows development testing).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $whitelistIps = config('midtrans.whitelist_ips', []);

        // If no whitelist is configured, allow all (development mode)
        if (empty($whitelistIps)) {
            return $next($request);
        }

        $clientIp = $request->ip();

        foreach ($whitelistIps as $cidr) {
            if ($this->ipInCidr($clientIp, $cidr)) {
                return $next($request);
            }
        }

        Log::warning("Midtrans webhook rejected: IP {$clientIp} not in whitelist.", [
            'whitelist' => $whitelistIps,
        ]);

        return response()->json(['message' => 'Forbidden'], 403);
    }

    /**
     * Check if an IP address falls within a CIDR range.
     *
     * @param  string  $ip      The IP address to check
     * @param  string  $cidr    The CIDR range (e.g. "103.30.1.0/24")
     * @return bool
     */
    private function ipInCidr(string $ip, string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            // Exact IP match (no CIDR notation)
            return $ip === $cidr;
        }

        [$subnet, $prefix] = explode('/', $cidr, 2);
        $prefix = (int) $prefix;

        if ($prefix < 0 || $prefix > 32) {
            return false;
        }

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $mask = -1 << (32 - $prefix);

        return ($ipLong & $mask) === ($subnetLong & $mask);
    }
}
