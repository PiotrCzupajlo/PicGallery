
use gallery_db;

// Create a new user for the application
db.createUser({
    user: "gallery_user",
    pwd: "secure_password", // Replace with a strong password
    roles: [
        { role: "readWrite", db: "gallery_db" } // Grant read and write access to this database
    ]
});

// Confirm the user was created
db.getUsers();
