<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
</style>

<body>

    <h1> List User Blade Template </h1>
    @php
        // $arrayProduct = [['id' => 2, 'name' => 'Product A', 'price' => 32000], ['id' => 3, 'name' => 'Product B', 'price' => 31000], ['id' => 4, 'name' => 'Product C', 'price' => 37000], ['id' => 5, 'name' => 'Product D', 'price' => 33000], ['id' => 6, 'name' => 'Product F', 'price' => 39000]];
        //$$arrayProduct = [];      
    @endphp
    <table border="1">
        <tr>STT</tr>
        <tr>Id</tr>
        <tr>Name</tr>
        <tr>Price</tr>
        @forelse ($arrayProduct as $key => $product)
            <tr class="<?= $key % 2 != 0 ? 'odd' : '' ?>">
                <td> <?= $key + 1 ?> </td>
                <td> <?= $product['id'] ?> </td>
                <td> <?= $product['name'] ?> </td>
                <td> <?= number_format($product['price'], 2)  ?> </td>
            </tr>
        @empty {
            <tr colspan="4"> No Product</tr>
            }
        @endforelse
    </table>
</body>
</html>
