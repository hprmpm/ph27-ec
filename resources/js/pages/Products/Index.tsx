import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { Product } from '@/types';
import { Button } from '@/components/ui/button';

interface ProductIndexProps {
    products: Product[];
    saleProducts: Product[];
}

export default function ProductIndex({ products, saleProducts }: ProductIndexProps) {
    const saleProductIds = new Set(saleProducts.map(p => p.id));

    const handleAddToCart = (productName: string, productId: number) => {
        router.post(route('cart.add'), {
            product_id: productId
        }, {
            preserveScroll: true,
            onSuccess: () => {
                alert(`${productName} をカートに追加しました。`);
            },
            onError: () => {
                alert('カートへの追加に失敗しました。');
            }
        });
    };

    return (
        <GuestLayout>
            <Head title="商品一覧" />
            <div className="container mx-auto p-4 md:p-6 lg:p-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold">商品一覧</h1>
                    <Link href={route('cart.index')}>
                        <Button>カートを見る</Button>
                    </Link>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    {products.map((product) => {
                        const isOnSale = saleProductIds.has(product.id);
                        const originalPrice = Math.round(product.price * 1.25);

                        return (
                            <div key={product.id} className="border rounded-lg shadow-lg bg-white dark:bg-gray-800 flex flex-col justify-between transition-transform duration-300 hover:scale-105 hover:shadow-2xl">
                                <div className="flex-grow p-4">
                                    <Link href={route('products.show', { id: product.id })}>
                                        <img src={product.image} alt={product.name} className="w-full aspect-square object-cover mb-4 rounded" />
                                        <h2 className="text-xl font-semibold text-gray-900 dark:text-gray-100 h-14">{product.name}</h2>
                                    </Link>
                                    <div className="mt-2 h-8 flex items-center">
                                        {isOnSale ? (
                                            <div className="flex items-baseline gap-3">
                                                <p className="text-gray-500 dark:text-gray-400 line-through">{originalPrice.toLocaleString()} 円</p>
                                                <p className="text-red-600 font-bold text-lg">{product.price.toLocaleString()} 円</p>
                                            </div>
                                        ) : (
                                            <p className="text-gray-700 dark:text-gray-300 text-lg">{product.price.toLocaleString()} 円</p>
                                        )}
                                    </div>
                                </div>
                                <div className="p-4 pt-0">
                                    <Button className="mt-4 w-full" onClick={() => handleAddToCart(product.name, product.id)}>
                                        カートに追加
                                    </Button>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </GuestLayout>
    );
}

