<!-- All Products -->
<?php
    $priceRanges = [
        'Under 200' => [0, 200],
        '200 - 400' => [201, 400],
        '400 - 700' => [401, 700],
        '700 - 1000' => [701, 1000],
        'Over 1000' => [1001, PHP_INT_MAX]
    ];

    $StorageMemoriesRanges = [
        'Under 8GB' => [-1, 8],
        '16 GB' => [16, 16],
        '32 GB' => [32, 32],
        '64 GB' => [64, 64],
        'Over 64GB' => [64, PHP_INT_MAX]
    ];

    $RamMemoriesRanges = [
        'Under 4GB RAM' => [-1, 1],
        '2 GB RAM' => [2, 2],
        '3 GB RAM' => [3, 3],
        '4 GB RAM' => [4, 4],
        'Over 4GB RAM' => [5, PHP_INT_MAX]
    ];

    $CoreNumberRanges = [
        '1' => [1],
        '2' => [2],
        '4' => [4],
        '6' => [6],
        '8' => [8]
    ];

    $TechnologyRanges = [
        '2G' => "2G",
        '3G' => "3G",
        '4G' => "4G",
        '5G' => "5G"
    ];

    $product_shuffle=$product->getData();
    foreach($product_shuffle as $key=>$item){
        if($item['item_stock']==0){
            unset($product_shuffle[$key]);
        }
    }
    $brand = array_map(function ($pro){ return $pro['item_brand']; }, $product_shuffle);
    $unique = array_unique($brand);
    sort($unique);
    $price_array = array_map(function ($pro){ return $pro['item_price']; }, $product_shuffle);
    sort($price_array);
    shuffle($product_shuffle);

    
    $user=array();
    if(isset($_SESSION['user_id'])){
        $user=get_user_info($con,$_SESSION['user_id']);
        $in_cart=$cart->getCartId($cart->getProductCart($_SESSION['user_id']));
    }
    else{
        $in_cart=$cart->getCartId($product->getData('cart'));
    }

    if (isset($_GET['reset_filters'])) {
        header("Location: ./");
        exit; 
    }

    if (isset($_GET['brands']) && is_array($_GET['brands'])) {
        $selected_brands = $_GET['brands'];
        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_brands) {
            return in_array($item['item_brand'], $selected_brands);
        });
    }

    if (isset($_GET['filter-price']) && is_array($_GET['filter-price'])) {
        $selected_price_ranges = array_map(function($json) {
            return json_decode($json, true);
        }, $_GET['filter-price']);

        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_price_ranges) {
            foreach ($selected_price_ranges as $range) {
                $item_price = floatval($item['item_price']);
                if (count($range) == 1) {
                    if ($item_price == $range[0]) return true;
                } else {
                    if ($item_price >= $range[0] && $item_price <= $range[1]) return true;
                }
            }
            return false;
        });
    }

    if (isset($_GET['filter-storage']) && is_array($_GET['filter-storage'])) {
        $selected_storage_ranges = array_map(function($json) {
            return json_decode($json, true);
        }, $_GET['filter-storage']);

        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_storage_ranges) {
            foreach ($selected_storage_ranges as $range) {
                $item_storage_memory = $item['item_storage_memory'];
                if ($item_storage_memory >= $range[0] && $item_storage_memory <= $range[1]) return true;
            }
            return false;
        });
    }

    if (isset($_GET['filter-ram']) && is_array($_GET['filter-ram'])) {
        $selected_ram_ranges = array_map(function($json) {
            return json_decode($json, true);
        }, $_GET['filter-ram']);

        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_ram_ranges) {
            foreach ($selected_ram_ranges as $range) {
                $ram_capacity = $item['item_ram_memory'];
                if ($ram_capacity >= $range[0] && $ram_capacity <= $range[1]) return true;
            }
            return false;
        });
    }

    if (isset($_GET['filter-core']) && is_array($_GET['filter-core'])) {
        $selected_core_numbers = array_map('intval', $_GET['filter-core']);

        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_core_numbers) {
            return in_array($item['item_core_number'], $selected_core_numbers);
        });
    }

    if (isset($_GET['filter-tech']) && is_array($_GET['filter-tech'])) {
        $selected_tech_options = $_GET['filter-tech'];

        $product_shuffle = array_filter($product_shuffle, function($item) use ($selected_tech_options) {
            return in_array($item['item_technology'], $selected_tech_options);
        });
    }



    // request method post
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['all_products_submit'])){
            // call method addToCart
            if(isset($_SESSION['user_id']))
            {
                $cart->addToCart($_POST['user_id'], $_POST['item_id']);
            }
            else{
                header("location:register/login.php");
            }
            
        }
    }

?>
<style>
    .container {
        display: flex;
        flex-wrap: wrap;
    }

    .sidebar-filters {
        flex: 1;
        max-width: 200px; /* Adjust based on your design */
        margin-right: 20px; /* Space between sidebar and grid */
    }

    .grid {
        flex: 3;
    }

    .reset-filters-button {
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: #000000;
        color: #ffffff;
        border-color: #ffffff;
        transition: background-color 0.5 ease;
    }

    .reset-filters-button:hover {
        background-color: #990000;
        border-color: #ffffff;
    }
</style>
<section id="all-products">
    <div class="container">
<!--        <h4 class="font-rubik font-size-20">All Products</h4>-->
<!--        <div id="filters" class="button-group font-baloo font-size-16">-->
<!--            <button class="btn is-checked" data-filter="*">All Brand</button>-->
<!--            --><?php
//                array_map(function ($brand){
//                    printf('<button class="btn" data-filter=".%s">%s</button>', $brand, $brand);
//                }, $unique);
//            ?>
<!--        </div>-->
        <form action="./" method="GET">
            <div class="sidebar-filters">
                <button type="submit" name="reset_filters" class="btn btn-secondary reset-filters-button">Reset filters</button>

                <h5>Filter by Brand</h5>
                <?php foreach ($unique as $brand): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="brands[]" value="<?php echo $brand; ?>" id="brand-<?php echo $brand; ?>"
                            <?php if (isset($_GET['brands']) && in_array($brand, $_GET['brands'])) echo 'checked'; ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="brand-<?php echo $brand; ?>">
                            <?php echo $brand; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <h5>Filter by Price</h5>
                <?php foreach ($priceRanges as $range => $values): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="filter-price[]"
                               value="<?php echo htmlspecialchars(json_encode($values)); ?>"
                               id="price-<?php echo $range; ?>"
                            <?php
                            // Check if this price range is currently selected
                            if (!empty($_GET['filter-price']) && is_array($_GET['filter-price'])) {
                                foreach ($_GET['filter-price'] as $selectedValue) {
                                    if (json_encode($values) === $selectedValue) {
                                        echo 'checked';
                                        break;
                                    }
                                }
                            }
                            ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="price-<?php echo $range; ?>">
                            <?php echo $range; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <h5>Filter by Storage</h5>
                <?php foreach ($StorageMemoriesRanges as $range => $values): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="filter-storage[]"
                               value="<?php echo htmlspecialchars(json_encode($values)); ?>"
                               id="storage-<?php echo $range; ?>"
                            <?php
                            // Check if this storage range is currently selected
                            if (!empty($_GET['filter-storage']) && is_array($_GET['filter-storage'])) {
                                foreach ($_GET['filter-storage'] as $selectedValue) {
                                    if (json_encode($values) === $selectedValue) {
                                        echo 'checked';
                                        break;
                                    }
                                }
                            }
                            ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="storage-<?php echo $range; ?>">
                            <?php echo $range; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <h5>Filter by Ram Memory</h5>
                <?php foreach ($RamMemoriesRanges as $range => $values): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="filter-ram[]"
                               value="<?php echo htmlspecialchars(json_encode($values)); ?>"
                               id="ram-<?php echo $range; ?>"
                            <?php
                            // Check if this RAM range is currently selected
                            if (!empty($_GET['filter-ram']) && is_array($_GET['filter-ram'])) {
                                foreach ($_GET['filter-ram'] as $selectedValue) {
                                    if (json_encode($values) === $selectedValue) {
                                        echo 'checked';
                                        break;
                                    }
                                }
                            }
                            ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="ram-<?php echo $range; ?>">
                            <?php echo $range; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <h5>Filter by Core Number</h5>
                <?php foreach ($CoreNumberRanges as $core => $values): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="filter-core[]"
                               value="<?php echo $values[0]; ?>"
                               id="core-<?php echo $core; ?>"
                            <?php
                            // Check if this core number is currently selected
                            if (!empty($_GET['filter-core']) && is_array($_GET['filter-core'])) {
                                if (in_array($values[0], $_GET['filter-core'])) {
                                    echo 'checked';
                                }
                            }
                            ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="core-<?php echo $core; ?>">
                            <?php echo $core; ?> Core
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <h5>Filter by Technology</h5>
                <?php foreach ($TechnologyRanges as $techKey => $techValue): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="filter-tech[]"
                               value="<?php echo $techValue; ?>"
                               id="tech-<?php echo $techKey; ?>"
                            <?php
                            // Check if this network technology option is currently selected
                            if (!empty($_GET['filter-tech']) && is_array($_GET['filter-tech'])) {
                                if (in_array($techValue, $_GET['filter-tech'])) {
                                    echo 'checked';
                                }
                            }
                            ?>
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="tech-<?php echo $techKey; ?>">
                            <?php echo $techValue; ?>
                        </label>
                    </div>
                <?php endforeach; ?>

            </div>
        </form>

        <div class="grid">
            <?php array_map(function ($item) use($in_cart){ ?>
            <div class="grid-item border m-2 <?php echo $item['item_brand'] ?? "Brand" ; ?>">
                <div class="item m-3" style="width: 200px;">
                    <div class="product font-rale">
                        <a href="<?php printf('%s?item_id=%s', 'product.php',  $item['item_id']); ?>"><img style="height:200px;width:200px;" src="<?php echo $item['item_image'] ?? "./assets/products/13.png"; ?>" alt="product1" class="img-fluid"></a>
                        <div class="text-center">
                            <h6><?php echo $item['item_name'] ?? "Unknown"; ?></h6>
                            <div class="rating text-warning font-size-12">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="far fa-star"></i></span>
                            </div>
                            <div class="price py-2">
                                <span>
                                    <?php if(isset($_SESSION['currency'])){
                                        if($_SESSION['currency']=="USD"){
                                            echo '$';
                                        }
                                        else{
                                            if($_SESSION['currency']=="RON"){
                                                echo 'RON';
                                            }
                                            else{
                                                if($_SESSION['currency']=="EUR"){
                                                    echo '€';
                                                }
                                                else{
                                                    if($_SESSION['currency']=="GBP"){
                                                        echo '£';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        echo '$';
                                    }
                                    
                                        if(isset($_SESSION['exchange_rate'])){
                                            echo number_format((double)(floor(($item['item_price'])*$_SESSION['exchange_rate'])),2,'.','') ?? '0'; 
                                        }
                                        else{
                                            echo $item['item_price'] ?? '0';
                                        }
                                        ?>
                                        </span>
                            </div>
                            <form method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?? '1'; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ??1; ?>">
                                <?php
                                    if (in_array($item['item_id'], $in_cart ?? []) && isset($_SESSION['user_id']) ){
                                            echo '<button type="submit" disabled class="btn btn-success font-size-12">Already in the Cart</button>';
                                        
                                    }else{
                                        echo '<button type="submit" name="all_products_submit" class="btn btn-warning font-size-12">Add to Cart</button>';
                                    }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php }, $product_shuffle) ?>
        </div>
    </div>

</section>
<!-- !All Products -->