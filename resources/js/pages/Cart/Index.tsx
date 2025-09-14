import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Trash2, ShoppingCart } from 'lucide-react';

interface CartItem {
    id: number;
    name: string;
    price: number;
    image: string;
    quantity: number;
    subtotal: number;
}

interface CartIndexProps {
    cartItems: CartItem[];
    total: number;
}

export default function CartIndex({ cartItems, total }: CartIndexProps) {
    const handleRemoveItem = (productId: number) => {
        router.post(route('cart.remove'), { product_id: productId }, {
            preserveScroll: true,
        });
    };

    const handleUpdateQuantity = (productId: number, quantity: number) => {
        if (quantity < 1) return;
        router.post(route('cart.update'), {
            product_id: productId,
            quantity: quantity,
        }, {
            preserveScroll: true,
        });
    };

    return (
        <GuestLayout>
            <Head title="ショッピングカート" />
            <div className="container mx-auto p-4 md:p-6 lg:p-8">
                <h1 className="text-3xl font-bold mb-6">ショッピングカート</h1>

                {cartItems.length > 0 ? (
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div className="lg:col-span-2">
                            <Card>
                                <CardHeader>
                                    <CardTitle>カート内の商品</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead className="w-[100px] hidden md:table-cell">商品</TableHead>
                                                <TableHead>価格</TableHead>
                                                <TableHead>数量</TableHead>
                                                <TableHead className="text-right">小計</TableHead>
                                                <TableHead></TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {cartItems.map(item => (
                                                <TableRow key={item.id}>
                                                    <TableCell className="font-medium">
                                                        <div className="flex items-center gap-4">
                                                            <img src={item.image} alt={item.name} className="w-16 h-16 object-cover rounded-md" />
                                                            <span className="font-semibold">{item.name}</span>
                                                        </div>
                                                    </TableCell>
                                                    <TableCell>{item.price.toLocaleString()} 円</TableCell>
                                                    <TableCell>
                                                        <div className="flex items-center gap-2">
                                                            <Button variant="outline" size="icon" onClick={() => handleUpdateQuantity(item.id, item.quantity - 1)}>-</Button>
                                                            <span className="w-8 text-center">{item.quantity}</span>
                                                            <Button variant="outline" size="icon" onClick={() => handleUpdateQuantity(item.id, item.quantity + 1)}>+</Button>
                                                        </div>
                                                    </TableCell>
                                                    <TableCell className="text-right">{item.subtotal.toLocaleString()} 円</TableCell>
                                                    <TableCell className="text-right">
                                                        <Button variant="ghost" size="icon" onClick={() => handleRemoveItem(item.id)}>
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Order Summary */}
                        <div>
                            <Card>
                                <CardHeader>
                                    <CardTitle>注文概要</CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="flex justify-between">
                                        <span>商品合計</span>
                                        <span>{total.toLocaleString()} 円</span>
                                    </div>
                                    <div className="flex justify-between text-sm text-muted-foreground">
                                        <span>送料</span>
                                        <span>無料</span>
                                    </div>
                                    <hr/>
                                    <div className="flex justify-between font-bold text-lg">
                                        <span>合計金額</span>
                                        <span>{total.toLocaleString()} 円</span>
                                    </div>
                                </CardContent>
                                <CardFooter className="flex flex-col gap-2">
                                    <Link href={route('checkout.index')} className="w-full">
                                        <Button className="w-full">購入手続きへ進む</Button>
                                    </Link>
                                    <Link href={route('products.index')} className="w-full">
                                        <Button variant="outline" className="w-full">買い物を続ける</Button>
                                    </Link>
                                </CardFooter>
                            </Card>
                        </div>
                    </div>
                ) : (
                    <Card className="text-center p-12">
                         <ShoppingCart className="mx-auto h-16 w-16 text-gray-400" />
                        <h2 className="mt-4 text-2xl font-semibold">カートは空です</h2>
                        <p className="mt-2 text-gray-500">お気に入りの商品を見つけに行きましょう。</p>
                        <Link href={route('products.index')} className="mt-6 inline-block">
                             <Button>お買い物を始める</Button>
                        </Link>
                    </Card>
                )}
            </div>
        </GuestLayout>
    );
}