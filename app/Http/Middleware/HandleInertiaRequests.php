<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'state' => $request->user()->state,
                    'postal_code' => $request->user()->postal_code,
                    'city' => $request->user()->city,
                    'street_address' => $request->user()->street_address,
                    'building' => $request->user()->building,
                    'phone_number' => $request->user()->phone_number,
                ] : null,
            ],
            'appearance' => session('appearance', 'system'),
            'cart' => [
                'count' => function () {
                    $cart = session('cart', []);
                    $count = 0;
                    foreach ($cart as $item) {
                        $count += $item['quantity'] ?? 0;
                    }
                    return $count;
                },
            ],
        ]);
    }
}