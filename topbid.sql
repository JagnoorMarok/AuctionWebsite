CREATE DATABASE topbid;
USE topbid;

-- Create `users` table to store user details
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('bidder', 'seller') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create `listings` table to store auction item details
CREATE TABLE listings (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    status ENUM('active', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create `bids` table to store bids placed on auction items
CREATE TABLE bids (
    bid_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    user_id INT NOT NULL,
    bid_amount DECIMAL(10, 2) NOT NULL,
    bid_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create `won_auctions` table to store details of auctions that are won (marked as sold)
CREATE TABLE won_auctions (
    won_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    seller_id INT NOT NULL,
    bidder_id INT NOT NULL,
    final_bid_amount DECIMAL(10, 2) NOT NULL,
    won_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (bidder_id) REFERENCES users(user_id) ON DELETE CASCADE
);
