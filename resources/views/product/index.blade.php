<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Products</h2>
    
    <!-- Export Button -->
    <a href="{{ route('product.export') }}" class="btn btn-primary mb-3">Export Products</a>
    
    <!-- Import Form -->
    <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="form-group">
            <label for="file">Import CSV:</label>
            <input type="file" class="form-control" name="file"/>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
 
    <!-- Display Products Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>SKU</th>
                <th>Description</th>
                <th>Images</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        @if($product->images)
                            @foreach(json_decode($product->images) as $image)
                                <img src="{{ Storage::url($image) }}" alt="image" width="50" height="50">
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('product.create') }}" class="btn btn-primary">Create Product</a>
</div>
</body>
</html>