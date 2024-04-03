<?php  include('server.php'); ?>

<?php
// initialize variables
$brand = "";
$name = "";
$price = null;
$image = "";
$stock = null;
$description = "";
$section="";
$id = 0;
$update = false;
$technology = "";
$core_number = null;
$ram_memory = null;
$storage_memory = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($db, "SELECT * FROM product WHERE item_id=$id");
    $n = mysqli_fetch_array($record);
    $brand = $n['item_brand'];
    $name = $n['item_name'];
    $price = $n['item_price'];
    $image = $n['item_image'];
    $stock = $n['item_stock'];
    $section = $n['item_section'];
    $description = $n['item_description'];
    $storage_memory = $n['item_storage_memory'];
    $ram_memory = $n['item_ram_memory'];
    $core_number = $n['item_core_number'];
    $technology = $n['item_technology'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mobile Shop</title>
    <link rel="stylesheet" type="text/css" href="admin-style.css">
    <style>
        body {
            margin: 0;
        }

        .topnav {
            overflow: hidden;
            background-color: #00A5C4;
            height:56px;
        }

        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 18px;
            height:60px;
            color:black;
            line-height:30px;
        }

        .topnav a:hover {
            color: black;
        }

        .topnav a.active {
            color: white;
            font-size: 21px;
        }

    </style>
</head>
<body>
<?php $results = mysqli_query($db, "SELECT * FROM product"); ?>
<div class="topnav">
    <div style="float:left">
        <a class="active" href="admin-index.php">Mobile Shop</a>
    </div>
    <div style="float:right">
        <a class="logout" href="../index.php">Logout</a>
    </div>
    <div style="display:flex; justify-content:center;">
        <a style="margin-right:70px" href="admin-products.php">Products</a>
        <a style="margin-right:70px" href="admin-users.php">Users</a>
        <a href="admin-orders.php">Orders</a>
    </div>
</div>
<div>
    <table>
        <thead>
        <tr>
            <th>Brand</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Section</th>
            <th>Description</th>
            <th>Storage Memory</th>
            <th>RAM Memory</th>
            <th>Core Number</th>
            <th>Technology</th>
            <th>Stock</th>
            <th colspan="2">Action</th>
        </tr>
        </thead>

        <?php while ($row = mysqli_fetch_array($results)) { ?>
            <tr>
            <tr>
                <td><?php echo $row['item_brand']; ?></td>
                <td><?php echo $row['item_name']; ?></td>
                <td><?php echo $row['item_price']; ?></td>
                <td><?php echo $row['item_image']; ?></td>
                <td><?php echo $row['item_storage_memory']; ?></td>
                <td><?php echo $row['item_ram_memory']; ?></td>
                <td><?php echo $row['item_core_number']; ?></td>
                <td><?php echo $row['item_technology']; ?></td>
                <td><?php echo $row['item_stock']; ?></td>
                <td><?php echo $row['item_section']; ?></td>
                <td><?php echo $row['item_description']; ?></td>
                <td>
                    <a href="admin-products.php?edit=<?php echo $row['item_id']; ?>" class="edit_btn" >Edit</a>
                </td>
                <td>
                    <a href="server.php?del=<?php echo $row['item_id']; ?>" class="del_btn">Delete</a>
                </td>
            </tr>
            </tr>
        <?php } ?>
    </table>
</div>
<div>
    <form method="post" id="add" action="server.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="input-group">
            <label>Brand</label>
            <input type="text" name="brand" value="<?php echo $brand; ?>">
        </div>
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
        </div>
        <div class="input-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo $price; ?>">
        </div>
        <div class="input-group">
            <label>Image</label>
            <input style="border:none;" type="file" onchange="CopyMe(this,'txtFileName');" class="form-control-file" name="imageUpload" id="upload-image">

            <input type="text" id="txtFileName" name="image" value="<?php echo $image;?>" readonly>
        </div>
        <div class="input-group">
            <label>Storage Memory</label>
            <input type="number" name="storage_memory" value="<?php echo $storage_memory; ?>">
        </div>
        <div class="input-group">
            <label>RAM Memory</label>
            <input type="number" name="ram_memory" value="<?php echo $ram_memory; ?>">
        </div>
        <div class="input-group">
            <label>Core Number</label>
            <input type="number" name="core_number" value="<?php echo $core_number; ?>">
        </div>
        <div class="input-group">
            <label>Technology</label>
            <input type="text" name="technology" value="<?php echo $technology; ?>">
        </div>
        <div class="input-group">
            <label>Stock</label>
            <input type="number" min="0" name="stock" value="<?php echo $stock; ?>">
        </div>
        <div class="input-group">
            <label>Section</label>
            <select name="product_section" id="product_section" style="width:95%;height:42px;font-size:16px">
                <?php if($section=="Top Sale"){
                    echo '<option selected value="Top Sale">Top Sale</option>';
                    echo '<option value="Special Price">Special Price</option>';
                    echo '<option value="New Phones">New Phones</option>';
                }
                else
                    ?>
                <?php if($section=="Special Price"){
                    echo '<option value="Top Sale">Top Sale</option>';
                    echo '<option selected value="Special Price">Special Price</option>';
                    echo '<option value="New Phones">New Phones</option>';
                }
                else
                    ?>
                <?php if($section=="New Phones"){
                    echo '<option value="Top Sale">Top Sale</option>';
                    echo '<option value="Special Price">Special Price</option>';
                    echo '<option selected value="New Phones">New Phones</option>';
                }
                else{
                    echo '<option value="Top Sale">Top Sale</option>';
                    echo '<option value="Special Price">Special Price</option>';
                    echo '<option value="New Phones">New Phones</option>';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label>Description</label>
            <textarea name="description" id="description" rows="10" cols="80" style="width:95%"><?php echo $description; ?></textarea>
        </div>
        <div class="input-group">
            <?php if ($update == true): ?>
                <button class="btn" type="submit" name="update" style="background: #556B2F;" >Update</button>
            <?php else: ?>
                <button class="btn" type="submit" name="save" >Save</button>
            <?php endif ?>
        </div>
    </form>
</div>
</main>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true}).panelInstance('description');
    });
</script>
<script src="admin.js"></script>
</body>
</html>