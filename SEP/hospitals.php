<?php
include("./includes/header.php");
require_once './includes/config.php';

// Get all active hospitals from database
$query = "SELECT hospital_id, hospital_name, address, location, status 
          FROM hospitals 
          WHERE status = 1 
          ORDER BY hospital_name";
$result = mysqli_query($conn, $query);

// Get unique cities for filter dropdown
$cities_query = "SELECT DISTINCT location FROM hospitals WHERE status = 1 ORDER BY location";
$cities_result = mysqli_query($conn, $cities_query);
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
                        <?php while($city = mysqli_fetch_assoc($cities_result)): ?>
                            <option value="<?php echo htmlspecialchars(strtolower($city['location'])); ?>">
                                <?php echo htmlspecialchars($city['location']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Sort By</label>
                    <select id="sortFilter">
                        <option value="name">Name A-Z</option>
                        <option value="city">City</option>
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
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($hospital = mysqli_fetch_assoc($result)): ?>
                    <div class="hospital-card" data-city="<?php echo htmlspecialchars(strtolower($hospital['location'])); ?>">
                        <div class="hospital-image">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div class="hospital-info">
                            <h3><?php echo htmlspecialchars($hospital['hospital_name']); ?></h3>
                            <div class="hospital-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($hospital['location']); ?></span>
                            </div>
                            <p class="hospital-details">
                                <strong>Address:</strong> <?php echo htmlspecialchars($hospital['address']); ?><br>
                                <strong>Type:</strong> Government Hospital<br>
                                <strong>Status:</strong> <span style="color: green;">Active</span>
                            </p>
                            <div class="hospital-actions">
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'parent'): ?>
                                    <a href="book_appointment.php?hospital_id=<?php echo $hospital['hospital_id']; ?>" class="btn-book">
                                        <i class="fas fa-calendar-check"></i> Book Appointment
                                    </a>
                                <?php elseif(!isset($_SESSION['user_id'])): ?>
                                    <a href="login.php?redirect=book" class="btn-book">
                                        <i class="fas fa-calendar-check"></i> Login to Book
                                    </a>
                                <?php endif; ?>
                                <button onclick="viewHospital(<?php echo $hospital['hospital_id']; ?>)" class="btn-view">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-hospitals">
                    <i class="fas fa-hospital" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <h3>No Hospitals Available</h3>
                    <p>There are no hospitals registered in the system yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Filter and sort functionality
document.addEventListener('DOMContentLoaded', function() {
    const hospitalCards = document.querySelectorAll('.hospital-card');
    const cityFilter = document.getElementById('cityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const searchBtn = document.getElementById('searchBtn');

    function filterAndSortHospitals() {
        const selectedCity = cityFilter.value.toLowerCase();
        const sortBy = sortFilter.value;
        
        // Get all hospitals as array
        let hospitals = Array.from(hospitalCards);
        
        // Filter by city
        if (selectedCity) {
            hospitals = hospitals.filter(card => {
                const city = card.getAttribute('data-city');
                return city === selectedCity;
            });
        }
        
        // Sort
        if (sortBy === 'name') {
            hospitals.sort((a, b) => {
                const nameA = a.querySelector('h3').textContent.toLowerCase();
                const nameB = b.querySelector('h3').textContent.toLowerCase();
                return nameA.localeCompare(nameB);
            });
        } else if (sortBy === 'city') {
            hospitals.sort((a, b) => {
                const cityA = a.getAttribute('data-city');
                const cityB = b.getAttribute('data-city');
                return cityA.localeCompare(cityB);
            });
        }
        
        // Clear and re-render
        const hospitalResults = document.getElementById('hospitalResults');
        hospitalResults.innerHTML = '';
        
        if (hospitals.length === 0) {
            hospitalResults.innerHTML = '<p class="no-results">No hospitals found. Try a different city.</p>';
        } else {
            hospitals.forEach(card => {
                hospitalResults.appendChild(card);
            });
        }
    }

    // Event listeners
    searchBtn.addEventListener('click', filterAndSortHospitals);
    cityFilter.addEventListener('change', filterAndSortHospitals);
    sortFilter.addEventListener('change', filterAndSortHospitals);
});

// Global functions
function viewHospital(hospitalId) {
    // Redirect to hospital details page or show modal
    window.location.href = 'hospital_details.php?id=' + hospitalId;
}

function bookHospital(hospitalId) {
    if(<?php echo isset($_SESSION['user_id']) && $_SESSION['role'] == 'parent' ? 'true' : 'false'; ?>) {
        window.location.href = 'book_appointment.php?hospital_id=' + hospitalId;
    } else {
        window.location.href = 'login.php?redirect=book&hospital_id=' + hospitalId;
    }
}
</script>

<?php
include("./includes/footer.php");
?>