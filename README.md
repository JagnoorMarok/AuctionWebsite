 # TopBid - Online Auction Platform

TopBid is an online auction platform where users can register as either bidders or sellers. Sellers can list items for auction, and bidders can place bids on these items. Once the auction ends, the item is marked as sold, and the winning bidder is recorded in the system.

## Features

- **Seller Dashboard**: 
  - Sellers can list items for auction.
  - Sellers can view bidding history and mark auctions as sold.
  
- **Bidder Dashboard**: 
  - Bidders can view active auctions and place bids.
  - Bidders can view their winning auctions.

- **Auction Management**: 
  - Auctions are managed by the sellers, and the status of the items is updated when the auction ends.
  - The highest bid wins the auction, and the item is marked as sold.

## Technologies Used

- **PHP**: Backend logic and handling user authentication.
- **MySQL**: Database management for storing user, auction, and bid data.
- **HTML/CSS**: Frontend for displaying auction listings and user dashboards.
- **JavaScript**: For dynamic content handling.
  
## Getting Started

### Prerequisites

- **XAMPP/WAMP**: Local server environment (PHP, MySQL).
- **Text Editor/IDE**: Visual Studio Code, Sublime Text, etc.

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/TopBid.git
    ```

2. Move the project to your local web server folder (e.g., `htdocs` for XAMPP).

3. Create a MySQL database and import the `topbid.sql` file (located in the root directory of the project) into the database.

4. Configure database connection in `db_connection.php` with your MySQL credentials.

5. Run the project on your local server by navigating to `http://localhost/TopBid/`.

### Usage

- Register as a **Seller** or **Bidder**.
- Sellers can create auctions, view bids, and mark auctions as sold.
- Bidders can place bids on items and view their winning auctions.

### Features Overview

- **Register**: Create an account as a seller or bidder.
- **Login**: Authentication for access to the appropriate dashboard.
- **Dashboard**: Displays a list of auctions, with relevant information such as current bids and bid times.
- **Mark as Sold**: Once an auction is closed, sellers can mark their listing as sold, and the winning bidder will be recorded in the system.
  
## Contributing

Feel free to fork this project and submit pull requests. Contributions to improve features, UI, or functionality are welcome!




