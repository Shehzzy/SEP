<?php
include("./includes/header.php");
?>
<!-- Hospitals Hero -->
<section class="page-hero">
    <div class="container">
        <h1>Find Vaccination Centers Near You</h1>
        <p>Partner hospitals across Pakistan where you can get your child vaccinated</p>
    </div>
</section>

<!-- Filter Section -->
<section class="hospital-filters">
    <div class="container">
        <div class="filter-box">
            <h3>Search Hospitals</h3>
            <div class="filters">
                <div class="filter-group">
                    <label>City</label>
                    <select id="cityFilter">
                        <option value="">All Cities</option>
                        <option value="karachi">Karachi</option>
                        <option value="lahore">Lahore</option>
                        <option value="islamabad">Islamabad</option>
                        <option value="rawalpindi">Rawalpindi</option>
                        <option value="faisalabad">Faisalabad</option>
                        <option value="peshawar">Peshawar</option>
                        <option value="quetta">Quetta</option>
                        <option value="multan">Multan</option>
                        <option value="hyderabad">Hyderabad</option>
                        <option value="gujranwala">Gujranwala</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Sort By</label>
                    <select id="sortFilter">
                        <option value="name">Name A-Z</option>
                        <option value="city">City</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
                <button id="searchBtn" class="btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Hospitals List -->
<section class="hospitals-list">
    <div class="container">
        <div id="hospitalResults" class="hospital-grid">
            <!-- Hospitals will be loaded here by JavaScript -->
        </div>
    </div>
</section>


<?php
include("./includes/footer.php");
?>
<script src="assets/js/hospitals.js"></script>
<script src="assets/js/index.js"></script>