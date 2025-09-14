@extends('layouts.base')

@section('content')
    <h2>商品一覧</h2>

    <ul>
        @foreach ($products as $product)
            <li>
                <a href="{{ route('products.show', ['id' => $product->id]) }}">
                    {{-- 写真展示 --}}
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" width="100">
                    {{ $product->name }}
                </a>
                {{ $product->price }} 円
            </li>
        @endforeach
    </ul>

    <h2>セール中の商品</h2>

    <ul>
        @foreach ($saleProducts as $product)
            <li>
                <a href="{{ route('products.show', ['id' => $product->id]) }}">
                    {{ $product->name }}
                </a>
                {{ $product->price }} 円
            </li>
        @endforeach
    </ul>
@endsection
