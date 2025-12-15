document.addEventListener('DOMContentLoaded', function() {
    // Sample hospital data for Pakistani hospitals
    const hospitals = [
        {
            id: 1,
            name: "Aga Khan University Hospital",
            city: "karachi",
            address: "Stadium Road, Karachi",
            type: "Private",
            rating: 4.8
        },
        {
            id: 2,
            name: "Lahore General Hospital",
            city: "lahore",
            address: "Lahore Cantt, Lahore",
            type: "Government",
            rating: 4.2
        },
        {
            id: 3,
            name: "Pakistan Institute of Medical Sciences",
            city: "islamabad",
            address: "G-8/3, Islamabad",
            type: "Government",
            rating: 4.5
        },
        {
            id: 4,
            name: "Shaukat Khanum Memorial Hospital",
            city: "lahore",
            address: "Johar Town, Lahore",
            type: "Private",
            rating: 4.9
        },
        {
            id: 5,
            name: "Civil Hospital Karachi",
            city: "karachi",
            address: "M. A. Jinnah Road, Karachi",
            type: "Government",
            rating: 4.0
        },
        {
            id: 6,
            name: "Holy Family Hospital",
            city: "rawalpindi",
            address: "Satellite Town, Rawalpindi",
            type: "Government",
            rating: 4.1
        },
        {
            id: 7,
            name: "Faisalabad Institute of Cardiology",
            city: "faisalabad",
            address: "Sargodha Road, Faisalabad",
            type: "Government",
            rating: 4.3
        },
        {
            id: 8,
            name: "Khyber Teaching Hospital",
            city: "peshawar",
            address: "University Road, Peshawar",
            type: "Government",
            rating: 4.2
        },
        {
            id: 9,
            name: "Bolan Medical Complex",
            city: "quetta",
            address: "Brewery Road, Quetta",
            type: "Government",
            rating: 4.0
        },
        {
            id: 10,
            name: "Nishtar Hospital",
            city: "multan",
            address: "Nishtar Road, Multan",
            type: "Government",
            rating: 4.1
        },
        {
            id: 11,
            name: "Liaquat National Hospital",
            city: "karachi",
            address: "National Stadium Road, Karachi",
            type: "Private",
            rating: 4.6
        },
        {
            id: 12,
            name: "Rawalpindi Medical University",
            city: "rawalpindi",
            address: "Tipu Road, Rawalpindi",
            type: "Government",
            rating: 4.2
        }
    ];

    const hospitalResults = document.getElementById('hospitalResults');
    const cityFilter = document.getElementById('cityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const searchBtn = document.getElementById('searchBtn');

    // Render hospitals
    function renderHospitals(hospitalList) {
        hospitalResults.innerHTML = '';
        
        if (hospitalList.length === 0) {
            hospitalResults.innerHTML = '<p class="no-results">No hospitals found. Try a different city.</p>';
            return;
        }
        
        hospitalList.forEach(hospital => {
            const hospitalCard = document.createElement('div');
            hospitalCard.className = 'hospital-card';
            
            hospitalCard.innerHTML = `
                <div class="hospital-image">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="hospital-info">
                    <h3>${hospital.name}</h3>
                    <div class="hospital-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${hospital.city.charAt(0).toUpperCase() + hospital.city.slice(1)}</span>
                    </div>
                    <p class="hospital-details">
                        <strong>Address:</strong> ${hospital.address}<br>
                        <strong>Type:</strong> ${hospital.type}<br>
                        <strong>Rating:</strong> ${hospital.rating}/5
                    </p>
                    <div class="hospital-actions">
                        <button onclick="bookHospital(${hospital.id})">
                            <i class="fas fa-calendar-check"></i> Book Appointment
                        </button>
                    </div>
                </div>
            `;
            
            hospitalResults.appendChild(hospitalCard);
        });
    }

    // Filter and sort hospitals
    function filterAndSortHospitals() {
        let filteredHospitals = [...hospitals];
        
        // Filter by city
        const selectedCity = cityFilter.value;
        if (selectedCity) {
            filteredHospitals = filteredHospitals.filter(hospital => hospital.city === selectedCity);
        }
        
        // Sort
        const sortBy = sortFilter.value;
        filteredHospitals.sort((a, b) => {
            if (sortBy === 'name') {
                return a.name.localeCompare(b.name);
            } else if (sortBy === 'city') {
                return a.city.localeCompare(b.city);
            } else if (sortBy === 'rating') {
                return b.rating - a.rating;
            }
            return 0;
        });
        
        renderHospitals(filteredHospitals);
    }

    // Search button click
    searchBtn.addEventListener('click', filterAndSortHospitals);
    
    // Filter on change
    cityFilter.addEventListener('change', filterAndSortHospitals);
    sortFilter.addEventListener('change', filterAndSortHospitals);

    // Initial render
    filterAndSortHospitals();
});

// Global functions for buttons
function viewHospital(hospitalId) {
    alert(`View details for hospital ID: ${hospitalId}\n\nIn the real system, this would show detailed hospital information.`);
}

function bookHospital(hospitalId) {
    alert(`Book appointment for hospital ID: ${hospitalId}\n\nIn the real system, this would redirect to booking page.`);
}