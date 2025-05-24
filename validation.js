/**
 * Form validation for hotel reservation system
 */

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for reservation form
    const reservationForm = document.getElementById('reservation-form');
    if (reservationForm) {
        reservationForm.addEventListener('submit', validateReservationForm);
    }
    
    // Date validation on change
    const checkInDate = document.getElementById('check-in-date');
    const checkOutDate = document.getElementById('check-out-date');
    
    if (checkInDate && checkOutDate) {
        checkInDate.addEventListener('change', function() {
            validateDateRange(checkInDate, checkOutDate);
        });
        
        checkOutDate.addEventListener('change', function() {
            validateDateRange(checkInDate, checkOutDate);
        });
    }
});

/**
 * Validate the entire reservation form
 * 
 * @param {Event} event The form submission event
 */
function validateReservationForm(event) {
    let isValid = true;
    
    // Clear previous error messages
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(element => {
        element.remove();
    });
    
    // Reset error class
    const inputs = document.querySelectorAll('.error');
    inputs.forEach(input => {
        input.classList.remove('error');
    });
    
    // Validate first name
    const firstName = document.getElementById('first-name');
    if (firstName && !firstName.value.trim()) {
        showError(firstName, 'First name is required');
        isValid = false;
    }
    
    // Validate last name
    const lastName = document.getElementById('last-name');
    if (lastName && !lastName.value.trim()) {
        showError(lastName, 'Last name is required');
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email');
    if (email) {
        if (!email.value.trim()) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }
    }
    
    // Validate phone
    const phone = document.getElementById('phone');
    if (phone) {
        if (!phone.value.trim()) {
            showError(phone, 'Phone number is required');
            isValid = false;
        } else if (!isValidPhone(phone.value)) {
            showError(phone, 'Please enter a valid phone number');
            isValid = false;
        }
    }
    
    // Validate check-in date
    const checkInDate = document.getElementById('check-in-date');
    if (checkInDate) {
        if (!checkInDate.value) {
            showError(checkInDate, 'Check-in date is required');
            isValid = false;
        } else if (!isValidDate(checkInDate.value)) {
            showError(checkInDate, 'Please enter a valid date');
            isValid = false;
        }
    }
    
    // Validate check-out date
    const checkOutDate = document.getElementById('check-out-date');
    if (checkOutDate) {
        if (!checkOutDate.value) {
            showError(checkOutDate, 'Check-out date is required');
            isValid = false;
        } else if (!isValidDate(checkOutDate.value)) {
            showError(checkOutDate, 'Please enter a valid date');
            isValid = false;
        }
    }
    
    // Validate date range
    if (checkInDate && checkOutDate && checkInDate.value && checkOutDate.value) {
        if (!isValidDateRange(checkInDate.value, checkOutDate.value)) {
            showError(checkOutDate, 'Check-out date must be after check-in date');
            isValid = false;
        }
    }
    
    // Validate room selection
    const roomId = document.getElementById('room-id');
    if (roomId && roomId.tagName === 'SELECT' && roomId.value === '') {
        showError(roomId, 'Please select a room');
        isValid = false;
    }
    
    // Validate number of guests
    const numGuests = document.getElementById('num-guests');
    if (numGuests) {
        if (!numGuests.value) {
            showError(numGuests, 'Number of guests is required');
            isValid = false;
        } else if (parseInt(numGuests.value) <= 0) {
            showError(numGuests, 'Number of guests must be positive');
            isValid = false;
        }
    }
    
    // If form is invalid, prevent submission
    if (!isValid) {
        event.preventDefault();
    }
}

/**
 * Validate date range when dates are changed
 * 
 * @param {HTMLElement} checkInElement The check-in date input
 * @param {HTMLElement} checkOutElement The check-out date input
 */
function validateDateRange(checkInElement, checkOutElement) {
    // Remove any existing error message
    const existingError = checkOutElement.nextElementSibling;
    if (existingError && existingError.classList.contains('error-message')) {
        existingError.remove();
    }
    
    checkOutElement.classList.remove('error');
    
    // Set minimum date for check-out
    if (checkInElement.value) {
        const minDate = new Date(checkInElement.value);
        minDate.setDate(minDate.getDate() + 1);
        
        const minDateStr = minDate.toISOString().split('T')[0];
        checkOutElement.min = minDateStr;
        
        // If check-out date is now invalid, clear it
        if (checkOutElement.value && !isValidDateRange(checkInElement.value, checkOutElement.value)) {
            checkOutElement.value = '';
        }
    }
    
    // Set maximum date for check-in
    if (checkOutElement.value) {
        const maxDate = new Date(checkOutElement.value);
        maxDate.setDate(maxDate.getDate() - 1);
        
        const maxDateStr = maxDate.toISOString().split('T')[0];
        checkInElement.max = maxDateStr;
        
        // If check-in date is now invalid, clear it
        if (checkInElement.value && !isValidDateRange(checkInElement.value, checkOutElement.value)) {
            checkInElement.value = '';
        }
    }
}

/**
 * Show error message for an input field
 * 
 * @param {HTMLElement} element The input element
 * @param {string} message The error message
 */
function showError(element, message) {
    // Add error class to the input
    element.classList.add('error');
    
    // Create error message element
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    
    // Insert error message after the input
    element.parentNode.insertBefore(errorElement, element.nextSibling);
}

/**
 * Validate email format
 * 
 * @param {string} email The email to validate
 * @return {boolean} True if valid, false otherwise
 */
function isValidEmail(email) {
    const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(email);
}

/**
 * Validate phone number format
 * 
 * @param {string} phone The phone number to validate
 * @return {boolean} True if valid, false otherwise
 */
function isValidPhone(phone) {
    // Allow various phone formats
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return re.test(phone);
}

/**
 * Validate date format (YYYY-MM-DD)
 * 
 * @param {string} date The date to validate
 * @return {boolean} True if valid, false otherwise
 */
function isValidDate(date) {
    // Check format
    if (!/^\d{4}-\d{2}-\d{2}$/.test(date)) {
        return false;
    }
    
    // Check if it's a valid date
    const d = new Date(date);
    if (isNaN(d.getTime())) {
        return false;
    }
    
    // Check if it's not in the past
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return d >= today;
}

/**
 * Validate date range
 * 
 * @param {string} checkIn The check-in date
 * @param {string} checkOut The check-out date
 * @return {boolean} True if valid, false otherwise
 */
function isValidDateRange(checkIn, checkOut) {
    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);
    
    return checkOutDate > checkInDate;
}
