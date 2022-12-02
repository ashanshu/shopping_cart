<?php


class Cart
{
    private $name;
    private $price;
    private $quantity;
    private $total;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = 1;
        $this->total = $this->quantity * $this->price;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return number_format($this->price, 2,".", "");
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotal() {
        return number_format($this->total, 2, ".", "");
    }

    public function addCart() {
        $this->quantity++;
        $this->total = $this->price * $this->quantity;
    }

    public function removeCart() {
        $this->quantity--;
        $this->total = $this->price * $this->quantity;
    }

}

// ######## please do not alter the following code ########
$products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
];
// ########################################################

function getPriceByName($name, $products) {
    foreach ($products as $product) {
        if ($product['name'] == $name) {
            $price = round($product['price'], 2);
            return $price;
        }
    }
    return null;
}

session_start();
$uniqid = uniqid();

$flag = 1;

if (!empty($_GET["uniqid"])) {
    if ($_SESSION["uniqid"] != $_GET["uniqid"])
        $_SESSION["uniqid"] = $_GET["uniqid"];
    else
        $flag = 0;
}else {
    $flag = 0;
}

$name = $_GET['name'];
$price = getPriceByName($name, $products);
if ($price == null) {
    echo "Warning: The item " . $name . " is not in Products List!";
}else {
    if ($flag && $_GET["action"] == "add" && !empty($_GET["name"])) {

        if (isset($_SESSION["cart"]) && array_key_exists($name, $_SESSION["cart"])) {
            $cart = $_SESSION["cart"][$name];
            $cart->addCart();
            $_SESSION["cart"][$name] = $cart;
        } else {
            $cart = new Cart($name, $price);
            $_SESSION["cart"][$name] = $cart;
        }
    } else if ($flag && $_GET["action"] == "remove" && !empty($_GET["name"])) {

        if (isset($_SESSION["cart"]) && array_key_exists($name, $_SESSION["cart"])) {
            $cart = $_SESSION["cart"][$name];
            $cart->removeCart();
            if ($cart->getQuantity() == 0) {
                // delete session key when quantity = 0
                unset($_SESSION["cart"][$name]);
            } else {
                $_SESSION["cart"][$name] = $cart;
            }
        }
    }

}

?>

<div>Products</div>
<table>
<?php
// Product List
foreach ($products as $product) {
    ?>
    <form method="post" action="Cart.php?action=add&name=<?php echo $product['name']; ?>&uniqid=<?php echo $uniqid; ?>">
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo number_format($product['price'],2,".", ""); ?></td>
            <td><input type="submit" value="Add to Cart"></td>
        </tr>
    </form>
<?php
    }
?>
</table>
<br>
<br>
<br>
<div>Cart</div>
<table>
<?php
// Cart List
$totals = 0;
foreach ($_SESSION["cart"] as $cart) {
    $totals += $cart->getTotal();
    ?>
    <form method="post" action="Cart.php?action=remove&name=<?php echo $cart->getName(); ?>&uniqid=<?php echo $uniqid; ?>">
        <tr>
            <td><?php echo $cart->getName(); ?></td>
            <td><?php echo $cart->getPrice(); ?></td>
            <td><?php echo $cart->getQuantity(); ?></td>
            <td><?php echo $cart->getTotal(); ?></td>
            <td><input type="submit" value="Remove from Cart"></td>
        </tr>
    </form>
    <?php
}

?>
</table>
<div><?php if ($totals == 0) echo "Cart is empty!"; else echo "Overall Total: " . number_format($totals, 2,".", ""); ?></div>

