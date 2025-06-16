<?php
session_start();
$conn = mysqli_connect("awseb-e-gmxuwapfep-stack-awsebrdsdatabase-in8izjrfk3kk.cak1tr3azd8u.us-east-1.rds.amazonaws.com", "carsqt", "123456789", "ass2");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>The Car Rentals</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <style>
        #searchBar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .container1 {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            width: 250px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            transition: 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .card p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .card .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #222;
        }

        .btn-add {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-add:hover {
            background-color: #45a049;
        }

        .btn-add:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>



   

    <script>
$(document).ready(function () {
    var availableTags = [];

    // Fetch all car data
    $.ajax({
        url: "getAllCarsAsJson.php",
        type: "get",
        dataType: "JSON",
        success: function (response) {
            const brandsSet = new Set();
            const typesSet = new Set();

            response.forEach(function (car) {
                // Collect unique brands and types
                brandsSet.add(car.brand);
                typesSet.add(car.carType);

                // Render card
                let card = `
                    <div class="card"
                        data-image="${car.image ? car.image : ''}"
                        data-brand="${car.brand}"
                        data-model="${car.carModel}"
                        data-type="${car.carType}"
                        data-fuel="${car.fuelType}"
                        data-price="${car.pricePDay}"
                        data-mileage="${car.mileage}"
                        data-availability="${car.availability}"
                        data-image="${car.image ? car.image : ''}"  
                        data-vin="${car.VIN}"
                        data-search="${car.carType.toLowerCase()} ${car.brand.toLowerCase()} ${car.carModel.toLowerCase()} ${car.mileage.toLowerCase()} ${car.fuelType.toLowerCase()}">
                        <div class="title">${car.brand} ${car.carModel}</div>
                        <img src="${car.image ? car.image : 'default-car.jpg'}" alt="${car.brand} ${car.carModel}" />
                        <p><strong>Brand:</strong> ${car.brand}</p>
                        <p><strong>Type:</strong> ${car.carType}</p>
                        <p><strong>Mileage:</strong> ${car.mileage}</p>
                        <p><strong>Fuel:</strong> ${car.fuelType}</p>
                        <p><strong>Price/Day:</strong> $${car.pricePDay}</p>
                        <p><strong>Availability:</strong> ${car.availability == "1" ? "Yes" : "No"}</p>
                        <button class="btn-add" ${car.availability == "0" ? "disabled" : ""}>Rent</button>
                    </div>`;
                $("#cardContainer").append(card);
            });

            // Populate Brand and Type Dropdowns
            $("#brandFilter").append(`<option value="">All Brands</option>`);
            brandsSet.forEach(brand => {
                $("#brandFilter").append(`<option value="${brand}">${brand}</option>`);
            });

            $("#typeFilter").append(`<option value="">All Types</option>`);
            typesSet.forEach(type => {
                $("#typeFilter").append(`<option value="${type}">${type}</option>`);
            });
        },
        error: function (xhr, status, error) {
            alert("AJAX Error: " + status + " - " + error);
        }
    });

    function applyFilters() {
        const searchVal = $(".search-bar").val().toLowerCase().trim();
        const selectedBrand = $("#brandFilter").val().toLowerCase();
        const selectedType = $("#typeFilter").val().toLowerCase();

        $(".card").each(function () {
            const brand = $(this).data("brand").toLowerCase();
            const model = $(this).data("model").toLowerCase();
            const type = $(this).data("type").toLowerCase();
            const fuel = $(this).data("fuel").toLowerCase();
            const mileage = $(this).data("mileage").toLowerCase();
            const price = $(this).data("price").toString();
            const availability = $(this).data("availability").toString();

            const searchContent = `${brand} ${model} ${type} ${fuel} ${mileage} ${price} ${availability}`;
            const matchesSearch = searchContent.includes(searchVal);
            const matchesBrand = !selectedBrand || brand === selectedBrand;
            const matchesType = !selectedType || type === selectedType;

            $(this).toggle(matchesSearch && matchesBrand && matchesType);
        });
    }

    function updateSearchSuggestions() {
        const value = $(".search-bar").val().toLowerCase().trim();
        const uniqueMatches = {
            brand: new Set(),
            model: new Set(),
            type: new Set(),
            fuel: new Set(),
            mileage: new Set(),
            price: new Set(),
            availability: new Set()
        };

        $(".card").each(function () {
            const brand = $(this).data("brand").toLowerCase();
            const model = $(this).data("model").toLowerCase();
            const type = $(this).data("type").toLowerCase();
            const fuel = $(this).data("fuel").toLowerCase();
            const mileage = $(this).data("mileage").toLowerCase();
            const price = $(this).data("price").toString();
            const availability = $(this).data("availability").toString();

            const searchable = `${brand} ${model} ${type} ${fuel} ${mileage} ${price} ${availability}`;
            const match = searchable.includes(value);

            if (match) {
                if (brand.includes(value)) uniqueMatches.brand.add(brand);
                if (model.includes(value)) uniqueMatches.model.add(model);
                if (type.includes(value)) uniqueMatches.type.add(type);
                if (fuel.includes(value)) uniqueMatches.fuel.add(fuel);
                if (mileage.includes(value)) uniqueMatches.mileage.add(mileage);
                if (price.includes(value)) uniqueMatches.price.add(price);
                if (availability.includes(value)) uniqueMatches.availability.add(availability);
            }
        });

        if (value === "") {
            $("#search-results").hide();
            return;
        }

        $("#search-results").empty().show();

        for (let category in uniqueMatches) {
            const matches = Array.from(uniqueMatches[category]);
            if (matches.length > 0) {
                $("#search-results").append(`<h4 style="color: black;">${capitalize(category)} Matches</h4>`);
                matches.forEach(item => {
                    $("#search-results").append(`<div class="result-item" data-value="${item}">${capitalize(item)}</div>`);
                });
            }
        }
    }

    // Search and filter logic
    $(".search-bar").on("keyup", function () {
        updateSearchSuggestions();
        applyFilters();
    });

    // Dropdown change events
    $("#brandFilter, #typeFilter").on("change", applyFilters);

    // Suggestion click handler
    $("#search-results").on("click", ".result-item", function () {
        const val = $(this).data("value");
        $(".search-bar").val(val);
        applyFilters();
        $("#search-results").empty().hide();
    });

     $("#cardContainer").on("click", ".btn-add:not(:disabled)", function () {
        const card = $(this).closest(".card");
        const vin = card.data("vin");

        $.ajax({
            url: "rentCar.php",
            type: "POST",
            data: { vin: vin },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    window.location.href = "rentCar.php";
                } else {
                    alert("Failed to select car.");
                }
            },
            error: function () {
                alert("Failed to rent car.");
            }
        });
});


    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
</script>


</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo"><img src="https://api.iconify.design/lucide/car.svg" alt="Car Icon" width="32" height="32" onclick="window.location.href='index.php'">

                    
                        <button class="button-heading" onclick="window.location.href='index.php'"><h1>The Car Rental Guys</h1></button>
                        

                    
                </div>
                <div class="header-button">
                    
                    <button class="btn-primary" onclick="window.location.href='rentCar.php'">
                        <!-- <a href="cart.html">Cart</a> -->
                         Reservations

                    </button>
                </div>
            </div>

            <div class="header-bottom">
                <nav class="categories">
                    <div class="category-item">
                        <label for="brandFilter" ><h4>Filter by Brand:</h4></label>
                        <select id="brandFilter">
                            <!-- <option value="">All Brands</option> -->
                        </select>
                    </div>
                    <div class="category-item">
                        <label for="typeFilter"><h4>Filter by Type:</h4></label>
                        <select id="typeFilter">
                            <!-- <option value="">All Types</option> -->
                        </select>
                    </div>
                    <!-- <div class="category-item">
                        <button class="category-btn">
                        <img src="https://api.iconify.design/lucide/home.svg" alt="Home Icon" width="24" height="24">                           
                        <a href="filterprods.php?category=2000">Home</a>                            
                            
                        </button>
                        <div class="dropdown-menu">
                        <a href="filterprods.php?category=2000&subcategory=3">Medicine</a>                            
                        <a href="filterprods.php?category=2000&subcategory=4">Everyday Use</a>      
                        <a href="filterprods.php?category=2000&subcategory=5">Cleaning</a>                           
                     
                        </div>
                    </div>
                    <div class="category-item">
                        <button class="category-btn">
                            <img src="https://api.iconify.design/lucide/apple.svg" alt="Fresh Icon" width="24" height="24">                            <a href="filterprods.php?category=3000">Fresh</a> 
                        </button>
                        <div class="dropdown-menu">
                        <a href="filterprods.php?category=3000&subcategory=6">Food</a>                            
                        <a href="filterprods.php?category=3000&subcategory=7">Fruits</a>                            
                                                    
                        </div>
                    </div>
                    <div class="category-item">
                        <button class="category-btn">
                            <img src="https://api.iconify.design/lucide/coffee.svg" alt="Beverages Icon" width="24" height="24">
                            <a href="filterprods.php?category=4000">Snacks</a> 
                        </button>
                        <div class="dropdown-menu">
                        <a href="filterprods.php?category=4000&subcategory=8">Tea</a>                            
                        <a href="filterprods.php?category=4000&subcategory=9">Coffee</a>                            
                        <a href="filterprods.php?category=4000&subcategory=10">Chocolate</a>                            
                        </div>
                    </div> -->
                    <!-- <div class="category-item">
                        <button class="category-btn">
                            <img src="https://api.iconify.design/lucide/dog.svg" alt="Pet Food Icon" width="24" height="24">
                            <a href="filterprods.php?category=5000">Pets</a> 
                        </button>
                        <div class="dropdown-menu">
                        <a href="filterprods.php?category=5000&subcategory=11">Dog Food</a>                            
                        <a href="filterprods.php?category=5000&subcategory=12">Bird Food</a>                            
                        <a href="filterprods.php?category=5000&subcategory=13">Cat Food</a>                            
                        <a href="filterprods.php?category=5000&subcategory=14">Fish Food</a>                            
                        </div>
                    </div> -->
                </nav>

                <div class="search-container">
                <!-- <img src="se.png" class="search-icon" /> -->
                <input type="text" class="search-bar" placeholder="Search for Cars..." />
                <div id="search-results"></div>
                </div>
                
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <!-- <div class="featured-grid">
                <div class="featured-card">
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80" alt="Fresh Produce">
                    <div class="card-content">
                        <h3>Fresh Produce</h3>
                        <p>Farm-fresh fruits and vegetables delivered daily</p>
                        <button class="btn-primary">Shop Now</button>
                    </div>
                </div>

                <div class="featured-card">
                    <img src="https://images.unsplash.com/photo-1534723452862-4c874018d66d?auto=format&fit=crop&q=80" alt="Fresh Bread">
                    <div class="card-content">
                        <h3>Freshly Baked</h3>
                        <p>Artisanal breads and pastries baked every morning</p>
                        <button class="btn-primary">Shop Now</button>
                    </div>
                </div>
            </div> -->
            <!-- <div class="welcome-section"></div>
            <h2>Welcome to The Basketeers Grocery Store!</h2>

            <p>Craving for groceries, my dear?</p>
            <p>No fear!</p>
            <p><strong>The Basketeers are here!</strong></p>
        
            <p>Check out the list of our products below:</p>
        
            <div class="content">
                <p text-color="white">We have a wide range of products to choose from.</p>
                <a href="show.php" class="btn-secondary">All Products</a>
            </div>
            </div> -->
               
               

           
        


        </div>
    </main>
<!-- 
     <input type="text" id="searchBar" placeholder="Search cars by type, brand, or model..." /> -->
    <div class="container1" id="cardContainer">
      
    </div>
      
</body>
</html>