import { Head, useForm, Link } from '@inertiajs/react';
import React, { useState } from 'react';
import GuestLayout from '@/layouts/GuestLayout';
import { User } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { InputError } from '@/components/input-error';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { CheckCircle } from 'lucide-react';

interface CartItem {
    id: number;
    name: string;
    price: number;
    image: string;
    quantity: number;
    subtotal: number;
}
interface CheckoutProps {
    user: User;
    cartItems: CartItem[];
    total: number;
}
interface CheckoutFormData {
    name: string;
    postal_code: string;
    city: string;
    street_address: string;
    building: string;
    phone_number: string;
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    [key: string]: any;
}

export default function CheckoutIndex({ user, cartItems, total }: CheckoutProps) {
    const [isModalOpen, setIsModalOpen] = useState(false);

    const { data, setData, post, processing, errors } = useForm<CheckoutFormData>({
        name: user.name ?? '',
        postal_code: user.postal_code ?? '',
        city: user.city ?? '',
        street_address: user.street_address ?? '',
        building: user.building ?? '',
        phone_number: user.phone_number ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('order'), {
            onSuccess: () => setIsModalOpen(true),
        });
    }

    return (
        <GuestLayout>
            <Head title="購入手続き" />
            <div className="container mx-auto p-4 md:p-6 lg:p-8">
                <h1 className="text-2xl font-bold mb-6">購入手続き</h1>

                <form onSubmit={submit} className="grid md:grid-cols-2 gap-8">
                    {/* 配送先情報 */}
                    <Card>
                        <CardHeader>
                            <CardTitle>配送先情報</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                             <div>
                                <Label htmlFor="name">お名前</Label>
                                <Input id="name" value={data.name} onChange={e => setData('name', e.target.value)} required />
                                <InputError message={errors.name} className="mt-2" />
                            </div>
                             <div>
                                <Label htmlFor="postal_code">郵便番号</Label>
                                <Input id="postal_code" value={data.postal_code} onChange={e => setData('postal_code', e.target.value)} required />
                                <InputError message={errors.postal_code} className="mt-2" />
                            </div>
                             <div>
                                <Label htmlFor="city">市区町村</Label>
                                <Input id="city" value={data.city} onChange={e => setData('city', e.target.value)} required />
                                <InputError message={errors.city} className="mt-2" />
                            </div>
                             <div>
                                <Label htmlFor="street_address">番地</Label>
                                <Input id="street_address" value={data.street_address} onChange={e => setData('street_address', e.target.value)} required />
                                <InputError message={errors.street_address} className="mt-2" />
                            </div>
                             <div>
                                <Label htmlFor="building">建物名・部屋番号</Label>
                                <Input id="building" value={data.building} onChange={e => setData('building', e.target.value)} />
                                <InputError message={errors.building} className="mt-2" />
                            </div>
                            <div>
                                <Label htmlFor="phone_number">電話番号</Label>
                                <Input id="phone_number" value={data.phone_number} onChange={e => setData('phone_number', e.target.value)} required />
                                <InputError message={errors.phone_number} className="mt-2" />
                            </div>
                        </CardContent>
                    </Card>

                    {/* 注文概要 */}
                    <div className="space-y-8">
                        <Card>
                            <CardHeader>
                                <CardTitle>ご注文内容</CardTitle>
                            </CardHeader>
                            <CardContent>
                                {cartItems.map(item => (
                                    <div key={item.id} className="flex justify-between items-center py-2 border-b">
                                        <div>
                                            <p className="font-semibold">{item.name}</p>
                                            <p className="text-sm text-gray-500">数量: {item.quantity}</p>
                                        </div>
                                        <p>{item.subtotal.toLocaleString()} 円</p>
                                    </div>
                                ))}
                                <div className="flex justify-between font-bold text-lg pt-4">
                                    <p>合計金額</p>
                                    <p>{total.toLocaleString()} 円</p>
                                </div>
                            </CardContent>
                        </Card>
                        <Button type="submit" className="w-full text-lg py-6" disabled={processing}>
                            {processing ? '処理中...' : '注文を確定する'}
                        </Button>
                    </div>
                </form>
            </div>

            <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle className="flex items-center gap-2 text-xl">
                           <CheckCircle className="text-green-500 h-8 w-8" />
                           ご注文完了
                        </DialogTitle>
                        <DialogDescription className="pt-4 text-base">
                            ご注文いただき、誠にありがとうございます。
                            発送の準備が整い次第、改めてご連絡いたします。
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter className="mt-4">
                        <Link href={route('products.index')} className="w-full">
                            <Button className="w-full">
                                買い物を続ける
                            </Button>
                        </Link>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </GuestLayout>
    );
}