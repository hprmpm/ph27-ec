import { usePage, Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { PageProps } from '@/types';
import { ShoppingCart } from 'lucide-react';

export default function GuestLayout({ children }: PropsWithChildren) {
    const { auth, cart } = usePage<PageProps>().props;

    return (
        <div className="min-h-screen bg-white dark:bg-black">
            <header className="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-10">
                <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex-shrink-0">
                            <Link href={route('products.index')} className="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                Tmart
                            </Link>
                        </div>

                        <div className="flex items-center space-x-6">
                            <Link href={route('cart.index')} className="relative text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                <ShoppingCart />
                                {cart.count > 0 && (
                                    <span className="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                        {cart.count}
                                    </span>
                                )}
                            </Link>

                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                >
                                    ダッシュボード
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >
                                        ログイン
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >
                                        登録
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </header>

            <main>{children}</main>
        </div>
    );
}