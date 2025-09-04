@extends('layouts.base')

@section('content')
    <h2>{{ $product->name }}</h2>
    <div>
        {{ $product->price }}円
    </div>
    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        @error('quantity')
            <p class="error-message">{{ $message }}</p>
        @enderror
        <input type="hidden" name="productId" value="{{ $product->id }}">
        <input type="number" name="quantity" value="{{ old('quantity') }}" @error('quantity') class="error-input" @enderror>
        <input type="submit" value="カートに入れる">
    </form>
@endsection
