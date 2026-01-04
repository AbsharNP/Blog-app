# Blog Application

A modern, feature-rich blog application built with Laravel and Tailwind CSS. This application provides a complete blogging platform with user authentication, post management, comments, likes, and more.

## Features

### Core Features
- ✅ **User Authentication** - Secure registration and login system using Laravel's built-in authentication
- ✅ **Post Management** - Create, read, and manage blog posts with images
- ✅ **View Count** - Track how many times each post has been viewed
- ✅ **Like System** - Users can like/unlike posts
- ✅ **Comments** - Users can comment on posts with real-time updates
- ✅ **User Profiles** - View user profiles with post statistics
- ✅ **Author Filtering** - Filter posts by specific authors
- ✅ **Dark Mode** - Toggle between light and dark themes
- ✅ **Responsive Design** - Fully responsive design that works on all devices

### Additional Features
- User profile pages with statistics (total posts, likes, comments, views)
- "My Posts" page to view all posts by the authenticated user
- Optimized database queries to prevent N+1 problems
- Clean and modern UI with Tailwind CSS
- Smooth transitions and animations
- Image upload support for blog posts

## Requirements

- PHP >= 8.2
- Composer
- Node.js and npm
- MySQL/PostgreSQL/SQLite
- Laravel 12.x

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd blog-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog_app
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

   For development with hot reload:
   ```bash
   npm run dev
   ```

   Visit `http://localhost:8000` in your browser.

## Usage

### Creating an Account
1. Click on "Register" in the navigation bar
2. Fill in your name, email, and password
3. Accept the terms and conditions
4. Click "Create Account"
5. You'll be redirected to the login page

### Creating a Post
1. Log in to your account
2. Click "Add Post" on the posts page
3. Fill in the title and content
4. Optionally upload a featured image
5. Click "Save Post"

### Viewing Posts
- Browse all posts on the main posts page
- Use the author filter to view posts by specific users
- Click on any post to view full details

### Interacting with Posts
- **Like**: Click the heart icon to like/unlike a post (requires login)
- **Comment**: Scroll to the comments section and add your comment (requires login)
- **View Count**: Each post displays the number of views

### User Profile
- Click on any author's name to view their profile
- View their post statistics and all their posts
- Click "My Posts" in the navigation (when logged in) to see your own posts

### Dark Mode
- Click the moon/sun icon in the navigation bar to toggle between light and dark themes
- Your preference is saved in your browser's local storage

## Project Structure

```
blog-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   │   └── AuthenticatedSessionController.php
│   │   ├── PostController.php
│   │   ├── PostActionController.php
│   │   └── ProfileController.php
│   └── Models/
│       ├── Post.php
│       ├── PostAction.php
│       └── User.php
├── database/
│   └── migrations/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── layouts/
│   │   ├── posts/
│   │   ├── profile/
│   │   └── partials/
│   └── css/
├── routes/
│   └── web.php
└── public/
    └── storage/
```

## Database Schema

### Posts Table
- `id` - Primary key
- `title` - Post title
- `slug` - URL-friendly post identifier
- `content` - Post content
- `image` - Featured image filename
- `created_by` - Foreign key to users table
- `views_count` - Number of views
- `timestamps` - Created and updated timestamps
- `deleted_at` - Soft delete timestamp

### Post Actions Table
- `id` - Primary key
- `post_id` - Foreign key to posts table
- `user_id` - Foreign key to users table
- `type` - Either 'like' or 'comment'
- `comment` - Comment text (nullable)
- `timestamps` - Created and updated timestamps

### Users Table
- Standard Laravel users table with authentication fields

## Technologies Used

- **Backend**: Laravel 12.x
- **Frontend**: Tailwind CSS, jQuery
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel's built-in authentication system

## Key Features Implementation

### View Count
Views are tracked automatically when a user visits a post. The count is incremented using Laravel's `increment()` method for efficiency.

### Like/Unlike System
The like system uses a toggle mechanism - clicking the like button will like the post if it's not liked, and unlike it if it is already liked. Uses AJAX for smooth user experience.

### Comments
Comments are added in real-time using AJAX. New comments appear immediately without page refresh. Comments are ordered by latest first.

### Author Filtering
The posts index page includes a dropdown filter to view posts by specific authors. Uses query parameters to maintain filter state.

### Dark Mode
Dark mode is implemented using Tailwind CSS's dark mode classes. The preference is stored in localStorage and persists across page reloads.

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
The project uses Laravel's coding standards. Use Pint for code formatting:
```bash
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues, questions, or contributions, please open an issue on the repository.

## Author

Built with ❤️ using Laravel and Tailwind CSS