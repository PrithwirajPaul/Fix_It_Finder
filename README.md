
=> FixItFinder is a web-based platform designed to help users find and connect with service providers for various needs. It allows users to search for services or service providers and offers a range of features, including user profiles, post management, and an interactive service request system.

Features:
1. User Registration & Login: Users can register and log in to the platform.
2. Post Management: Users can create, view, and interact with posts related to services.
3. Profile Management: Users can view and edit their profiles, including uploading and changing their profile pictures.
4. Search Functionality: Search posts or service providers by keywords.
5. Interaction System: Users can like, save, and accept service requests, with detailed modal views for each post.
6. Service Provider Ratings: Service providers are rated based on their completed work, helping others choose the right provider.
Profile Picture Upload
7. Users can change their profile picture by clicking on the current picture. After selecting a new image, it is uploaded to the server and stored in the database.
8. The application uses a MySQL database for storing user information, posts, interactions, and profile pictures.


Key Tables:
1. info_table: Stores user information including profile picture paths.
2. post_manage: Contains data related to user posts.
3. interaction: Tracks user interactions such as likes, saves, and acceptances.


Installation Prerequisites:

PHP 7.0 or higher
MySQL
Apache Server
Bootstrap (CDN used for styling)
Steps:

Clone the repository:
git clone https://github.com/PrithwirajPaul/Fix_It_Finder

Navigate to the project directory:
cd fixitfinder

Import the database using the provided SQL file.
Update database credentials in database.php.
Run the project on your local server.


Contributing-
Contributions are welcome! Please follow the standard GitHub flow:

--> Fork the repository.
--> Create a new branch.
--> Make your changes.
--> Submit a pull request.


License
This project is licensed under the MIT License.