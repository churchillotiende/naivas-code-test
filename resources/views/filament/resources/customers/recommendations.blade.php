<ul>
    @foreach ($products as $product)
        <li>{{ $product->name }} (ID: {{ $product->id }})</li>
    @endforeach
</ul>
