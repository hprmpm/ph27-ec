@extends('layouts.base')

@section('content')
    <h1>カート</h1>
    @foreach ($items as $item)
        <div>
            {{ $item['product']->name }}
            {{ $item['product']->price }} 円
            {{ $item['quantity'] }} 個
        </div>
    @endforeach
    @if ($totalPrice > 0)
        <div>
            合計金額: {{ $totalPrice }}円
        </div>
    @endif
    <form action={{ route('cart.destroy') }} method="POST">
        @csrf
        @method('DELETE')
        <input type="submit" value="カートを空にする">
    </form>
    <form action={{ route('order') }} method="POST">
        @csrf
        <input type="submit" value="購入する">
    </form>
@endsection
