Dropplets v2.2
======================================

## Manage Your Blog:

- Add a new post at `https://(your url)/write`

- Change settings at `https://(your url)/setup`

## Project Goals:

- 30 second install and setup
- Simple post publishing form
- Quick password-only access
- Database not required

## Building Templates

Dropplets has two required files for templates:

- `home.php` is the page that lists the posts
- `post.php` is the page that displays a single post

There are a number of variables available for templates:

- `$siteConfig` is an array containing all of the site information
- `$allPosts` is an array containing all of the posts for page
- `$page` is the current page for pagination
- `$limit` is the number of posts to display per page
- `$post` is an array containing the data for one post
