<?php
session_start();

// Connect to DB
$conn = mysqli_connect("awseb-e-gmxuwapfep-stack-awsebrdsdatabase-in8izjrfk3kk.cak1tr3azd8u.us-east-1.rds.amazonaws.com", "carsqt", "123456789", "ass2");

// Handle POST AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // VIN selected
    if (isset($_POST['vin'])) {
        $_SESSION['selected_vin'] = $_POST['vin'];
        echo json_encode(['status' => 'success']);
        exit;
    }

    // Save form data to session (auto-save)
    if (isset($_POST['saveForm'])) {
        $_SESSION['rent_form_data'] = $_POST;
        exit; // no need for response
    }

    // On order submit
    if (isset($_POST['order'])) {
    unset($_SESSION['rent_form_data']);

    $vin = $_SESSION['selected_vin'] ?? null;
    if ($vin) {
        // Check if car is still available
        $checkStmt = $conn->prepare("SELECT availability FROM cars WHERE VIN = ?");
        $checkStmt->bind_param("s", $vin);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $row = $checkResult->fetch_assoc();
        if (!$row || $row['availability'] == 0) {
            echo json_encode(['status' => 'unavailable', 'message' => 'Sorry, this car is no longer available.']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE cars SET availability = 0 WHERE VIN = ?");
        $stmt->bind_param("s", $vin);
        $stmt->execute();

        unset($_SESSION['selected_vin']);

        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'VIN not found.']);
    }
    exit;
}


    // On cancel
    if (isset($_POST['cancelOrder'])) {
        unset($_SESSION['selected_vin']);
        // Optionally keep or clear form data
        unset($_SESSION['rent_form_data']);
        echo json_encode(['status' => 'success', 'message' => 'Order cancelled.']);
        exit;
    }
}


// Default message
$message = "";
$car = null;

// Get selected VIN and fetch car
$vin = $_SESSION['selected_vin'] ?? null;

// --- Get form data from session if available ---
$formData = $_SESSION['rent_form_data'] ?? [];

if (!$vin) {
    $message = "No car selected."."\n"."Please select a car to rent.";
} else {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE VIN = ?");
    $stmt->bind_param("s", $vin);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $message = "Car not available or already booked.";
    } else {
        $car = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css" />
    <title>Rent Car<?php echo $car ? ' - ' . htmlspecialchars($car['brand'] . ' ' . $car['carModel']) : ''; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .car-detail { border: 1px solid #ccc; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto; }
        .car-detail img { max-width: 100%; border-radius: 10px; }
        .order-form { margin-top: 30px; }
        .order-form input, .order-form button { display: block; margin-bottom: 10px; width: 100%; padding: 8px; }
        .message { color: red; text-align: center; font-size: 1.2em; margin: 20px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo"><img src="https://api.iconify.design/lucide/car.svg" alt="Car Icon" width="32" height="32"onclick="window.location.href='index.php'">

                    
                        <button class="button-heading" onclick="window.location.href='index.php'"><h1>The Car Rental Guys</h1></button>
                        

                    
                </div>
                <div class="header-button">
                    
                    <button class="btn-primary" onclick="window.location.href='rentCar.php'">
                        <!-- <a href="cart.html">Cart</a> -->
                         Reservations

                    </button>
                </div>
            </div>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <!-- base64 = base64_encode($field_v);
                    $img_src = 'data:image/jpeg;base64,' . $base64;
                    echo "<img src='$img_src' alt='Product Image'>"; -->
    <?php if ($car): ?>
    <main class="main">
        <div class="container">
            <div class="car-detail">
                <h1><?php echo htmlspecialchars($car['brand'] . ' ' . $car['carModel']); ?></h1>
                <?php if (!empty($car['image'])): 
                    // Encode image to base64
                    $base64 = base64_encode($car['image']);
                    // Use data URI scheme to embed the image
                    $car['image'] = 'data:image/jpeg;base64,' . $base64;
                    // Note: If the image is stored as a file, you would need to read it from the filesystem.
                    // For example, if it's stored in a file, you would use file_get_contents() to get the binary data.
                ?>

                    <img src="<?php echo htmlspecialchars($car['image']); ?>" align="center" style="width: 100%; height: auto; border-radius: 10px;">
                <?php endif; ?>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($car['carType']); ?></p>
                <p><strong>Mileage:</strong> <?php echo htmlspecialchars($car['mileage']); ?></p>
                <p><strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuelType']); ?></p>
                <p><strong>Price per Day:</strong> $<?php echo htmlspecialchars($car['pricePDay']); ?></p>
                <p><strong>Availability:</strong> <?php echo $car['availability'] == "1" ? "Yes" : "No"; ?></p>
            </div>

            <div class="order-form">
                <h2>Place Order</h2>
                <form name="form1" id="form1">
                    <table>
                        <tr>
                            <td>First Name:<span style="color:red">*</span></td>
                            <td><input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>Last Name:<span style="color:red">*</span></td>
                            <td><input type="text" id="password" name="password" required value="<?php echo htmlspecialchars($formData['password'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>Email:<span style="color:red">*</span></td>
                            <td><input type="email" name="email" required value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>Phone:<span style="color:red">*</span></td>
                            <td><input type="tel" name="phone" pattern="\d{10,}" minlength="10" required value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>License Number:<span style="color:red">*</span></td>
                            <td><input type="text" name="license" required value="<?php echo htmlspecialchars($formData['license'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>Start Date:<span style="color:red">*</span></td>
                            <td><input type="date" name="start_date" required value="<?php echo htmlspecialchars($formData['start_date'] ?? ''); ?>"></td>
                        </tr>
                        <tr>
                            <td>Number of Days:<span style="color:red">*</span></td>
                            <td><input type="number" name="days" id="days" value="<?php echo htmlspecialchars($formData['days'] ?? '1'); ?>" min="1" required></td>
                        </tr>
                        <tr>
                            <td>Total Price: $<span id="totalPrice"><?php echo htmlspecialchars($car['pricePDay']); ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="1" align="center">
                                <input type="submit" value="Cancel Order" id="cancel" class="btn-primary">
                            </td>
                            <td colspan="1" align="center">
                                <input type="submit" value="Place Order" id="submit" class="btn-primary">
                            </td>
                        </tr>
                    </table>
                </form>
                <div id="orderMsg" style="text-align:center; margin-top:10px;"></div>
            </div>
        </div>
    </main>

    <script>
    $(document).ready(function() {
    const $form = $("#form1");
    const $submit = $("#submit");
    const pricePerDay = <?php echo json_encode((float)$car['pricePDay']); ?>;
    const $days = $("#days");
    const $totalPrice = $("#totalPrice");

    // --- Live feedback elements ---
    // Add feedback spans after each input
    $form.find("input").each(function() {
        if (!$(this).next().hasClass("input-feedback")) {
            $(this).after('<span class="input-feedback" style="color:red;font-size:0.9em;margin-left:8px;"></span>');
        }
    });

    function updateTotalPrice() {
        const days = parseInt($days.val(), 10) || 1;
        $totalPrice.text((pricePerDay * days).toFixed(2));
    }

    function checkFormValidity() {
        $submit.prop("disabled", !$form[0].checkValidity());
    }

    // --- Live validation feedback ---
    $form.on("input change", "input", function() {
        const $input = $(this);
        const val = $input.val();
        const name = $input.attr("name");
        let msg = "";

        if ($input.prop("required") && !val) {
            msg = "Required";
        } else if (name === "email") {
            // Simple email regex
            if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                msg = "Invalid email";
            }
        } else if (name === "phone") {
            if (val && !/^\d{10,}$/.test(val)) {
                msg = "Enter at least 10 digits";
            }
        } else if (name === "days") {
            if (val && (parseInt(val, 10) < 1)) {
                msg = "Must be at least 1";
            }
        } else if (name === "start_date") {
            if (val) {
                const today = new Date();
                today.setHours(0,0,0,0);
                const inputDate = new Date(val);
                if (inputDate < today) {
                    msg = "Start date cannot be before today";
                }
            }
        }
        $input.next(".input-feedback").text(msg);

        updateTotalPrice();
        checkFormValidity();
    });

    // Initial feedback check
    $form.find("input").trigger("input");

        // AJAX submit
    $form.on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "rentCar.php",
            type: "POST",
            data: $form.serialize() + "&order=1",
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    $("#orderMsg").text(res.message).css("color", "green");
                    $submit.prop("disabled", true);
                    alert("Order placed successfully! Please continue shopping.");
                    setTimeout(() => window.location.href = 'index.php', 1000);
                } else if (res.status === "unavailable") {
                    $("#orderMsg").text(res.message).css("color", "red");
                    alert("Sorry, this car is no longer available.");
                    setTimeout(() => window.location.href = 'index.php', 1500);
                }
            },
            error: function() {
                $("#orderMsg").text("Order failed.").css("color", "red");
            }
        });
    });
        // AJAX cancel
    $("#cancel").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: "rentCar.php",
            type: "POST",
            data: { cancelOrder: 1 },
            dataType: "json",
            success: function(res) {
                $("#orderMsg").text(res.message).css("color", "red");

                // Reset form
                $("#form1")[0].reset();
                $("#totalPrice").text(pricePerDay.toFixed(2));
                $submit.prop("disabled", true);
                $("#orderMsg").text(res.message).css("color", "red");
                setTimeout(() => window.location.href = 'index.php', 1000);
            },
            error: function() {
                $("#orderMsg").text("Cancellation failed.").css("color", "red");
            }
        });
    });
    // Save form data to session on any input change
        $form.on("change", "input", function () {
            $.ajax({
                url: "rentCar.php",
                type: "POST",
                data: $form.serialize() + "&saveForm=1"
            });
        });

    });
    </script>
    <?php endif; ?>
</body>
</html>
