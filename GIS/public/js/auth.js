// Simple user storage (in a real app, this would be a database)
let users = JSON.parse(localStorage.getItem('users')) || [];

// Login form handler
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Find user
        const user = users.find(u => u.email === email && u.password === password);
        
        if (user) {
            // Store current user in session
            sessionStorage.setItem('currentUser', JSON.stringify(user));
            // Redirect to dashboard
            window.location.href = 'index.html';
        } else {
            alert('Invalid email or password');
        }
    });
}

// Signup form handler
const signupForm = document.getElementById('signupForm');
if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fullName = document.getElementById('fullName').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        // Validate passwords match
        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }
        
        // Check if email already exists
        if (users.some(u => u.email === email)) {
            alert('Email already registered');
            return;
        }
        
        // Create new user
        const newUser = {
            fullName,
            email,
            password
        };
        
        // Add user to storage
        users.push(newUser);
        localStorage.setItem('users', JSON.stringify(users));
        
        // Store current user in session
        sessionStorage.setItem('currentUser', JSON.stringify(newUser));
        
        // Redirect to dashboard
        window.location.href = 'index.html';
    });
}

// Logout handler
const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function() {
        sessionStorage.removeItem('currentUser');
        window.location.href = 'login.html';
    });
}

// Display user information
const userInfo = document.getElementById('userInfo');
if (userInfo) {
    const currentUser = JSON.parse(sessionStorage.getItem('currentUser'));
    if (currentUser) {
        userInfo.textContent = `Logged in as ${currentUser.fullName}`;
    }
}

// Check authentication on protected pages
function checkAuth() {
    const currentUser = sessionStorage.getItem('currentUser');
    if (!currentUser && !window.location.pathname.includes('login.html') && !window.location.pathname.includes('signup.html')) {
        window.location.href = 'login.html';
    }
}

// Run auth check on page load
document.addEventListener('DOMContentLoaded', checkAuth); 